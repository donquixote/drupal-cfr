<?php

namespace Drupal\cfrplugin\Controller;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Util\HtmlUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;
use Drupal\cfrplugin\Form\Form_IfaceDemo;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrplugin\RouteHelper\ClassRouteHelper;
use Drupal\cfrplugin\Util\UiCodeUtil;
use Drupal\cfrplugin\Util\UiDumpUtil;
use Drupal\cfrreflection\Util\StringUtil;
use Drupal\controller_annotations\Configuration\Cache;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\controller_annotations\RouteModifier\Annotated\RouteTitleMethod;
use Drupal\controller_annotations\RouteModifier\RouteIsAdmin;
use Drupal\controller_annotations\RouteModifier\RouteParameters;
use Drupal\controller_annotations\RouteModifier\RouteRequirePermission;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\routelink\RouteModifier\RouteDefaultTaskLink;
use Drupal\routelink\RouteModifier\RouteTaskLink;

/**
 * @Route("/admin/reports/cfrplugin/{interface}")
 * @Cache(expires="tomorrow")
 * @RouteIsAdmin
 * @RouteTitleMethod("title")
 * @RouteRequirePermission("view cfrplugin report")
 * @RouteParameters(interface = "cfrplugin:interface")
 *
 * @see \Drupal\cfrplugin\ParamConverter\ParamConverter_Iface
 */
class Controller_ReportIface extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $interface
   * @param string $methodName
   *
   * @return \Drupal\cfrplugin\RouteHelper\ClassRouteHelperInterface
   */
  public static function route($interface, $methodName = 'listOfPlugins') {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => _cfrplugin_interface_slug($interface),
      ],
      $methodName);
  }

  /**
   * Title callback for the route below.
   *
   * @param string $interface
   *
   * @return string
   */
  public function title($interface) {
    return StringUtil::interfaceGenerateLabel($interface);
  }

  /**
   * @Route
   * @RouteDefaultTaskLink("List of plugins")
   *
   * @param string $interface
   *
   * @return array
   */
  public function listOfPlugins($interface) {

    $services = CfrPluginHub::getContainer();
    $definitionToLabel = $services->definitionToLabel;
    $definitionToGroupLabel = $services->definitionToGrouplabel;
    $schemaToConfigurator = $services->schemaToConfigurator;

    // @todo THis should be a service.
    $definitionToSchema = DefinitionToSchema_Mappers::create();


    $definitionMap = $services->typeToDefmap->typeGetDefmap($interface);

    $rows = [];
    $rows_grouped = [];
    foreach ($definitionMap->getDefinitionsById() as $key => $definition) {

      try {
        // Just check if anything blows up.
        $schema = $definitionToSchema->definitionGetSchema($definition);
        $schemaToConfigurator->schemaGetConfigurator($schema);
        $ok = TRUE;
      }
      catch (\Exception $e) {
        $ok = FALSE;
      }

      $row = [

        Controller_ReportPlugin::route($interface, $key)->link(
          $definitionToLabel->definitionGetLabel($definition, $key)),

        Markup::create('<code>' . HtmlUtil::sanitize($key) . '</code>'),

        self::route($interface)->subpage('demo')->link(
          t('Demo'),
          [
            'query' => [
              'plugin[id]' => $key,
              'noshow' => TRUE,
            ],
          ]),

        $ok ? t('') : t('Broken'),

        UiCodeUtil::exportHighlightWrap($definition),
      ];

      if (NULL !== $groupLabelOrNull = $definitionToGroupLabel->definitionGetLabel($definition, null)) {
        $rows_grouped[$groupLabelOrNull][] = $row;
      }
      else {
        $rows[] = $row;
      }
    }

    foreach ($rows_grouped as $groupLabel => $rowsInGroup) {
      $rows[] = [
        [
          'colspan' => 5,
          'data' => ['#markup' => '<h3>' . HtmlUtil::sanitize($groupLabel) . '</h3>'],
        ],
      ];
      foreach ($rowsInGroup as $row) {
        $rows[] = $row;
      }
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

  /**
   * @Route("/code")
   * @RouteTaskLink("Code")
   *
   * @param string $interface
   *
   * @return array
   */
  public function code($interface) {

    $html = UiCodeUtil::classGetCodeAsHtml($interface);

    # dpm($html, __METHOD__);

    return [
      '#children' => $html,
    ];
  }

  /**
   * @Route("/demo")
   * @RouteTaskLink("Demo")
   *
   * @param string $interface
   *
   * @return array
   */
  public function demo($interface) {

    $settings = isset($_GET['plugin'])
      ? $_GET['plugin']
      : [];

    $out = [];

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    $out['form'] = $this->formBuilder()->getForm(
      Form_IfaceDemo::class,
      $interface);

    if (!empty($_GET['noshow']) || !empty($_POST)) {
      return $out;
    }

    $configurator = cfrplugin()->interfaceGetConfigurator($interface);

    if (!$settings) {
      return $out;
    }

    $out['summary'] = [
      '#type' => 'fieldset',
      '#title' => t('Plugin summary'),
      'summary' => [
        '#markup' => $configurator->confGetSummary($settings, new SummaryBuilder_Static()),
      ],
    ];

    $out['conf'] = [
      '#type' => 'fieldset',
      '#title' => t('Configuration data'),
      'data' => [
        '#children' => UiCodeUtil::exportHighlightWrap($settings),
      ],
    ];

    try {
      $object = $configurator->confGetValue($settings);
      if ($object instanceof $interface) {
        $out['object'] = UiDumpUtil::dumpDataInFieldset($object, t('Behavior object'));
      }
      else {
        # kdpm($object, 'Value from Configurator*::confGetValue()');
        drupal_set_message(
          t(
            'The @configurator_class::confGetValue() method had an unexpected return value.',
            [
              '@configurator_class' => get_class($configurator),
            ]),
          'warning');
        $out['object'] = UiDumpUtil::dumpDataInFieldset($object, t('Unexpected value or object'));

        # \Drupal\krumong\dpm(get_defined_vars());
      }
    }
    catch (\Exception $e) {
      if ($e instanceof EvaluatorException) {
        drupal_set_message(t('The configuration could not be evaluated.'), 'warning');
      }
      else {
        drupal_set_message(
          t(
            'The @configurator_class::confGetValue() method threw a @exception_class exception.',
            [
              '@configurator_class' => get_class($configurator),
              '@exception_class' => get_class($e),
            ]),
          'warning');
      }
      $out['exception'] = [
          '#type' => 'fieldset',
          '#title' => t('Exception'),
          '#description' => '<p>' . t('Cfrplugin was unable to generate a behavior object for the given configuration.') . '</p>',
        ]
        + UiDumpUtil::displayException($e);
    }

    $out['codegen'] = [
      '#type' => 'fieldset',
      '#title' => t('Generated PHP code'),
    ];

    $php = $configurator->confGetPhp($settings, new CfrCodegenHelper());
    $php = CodegenUtil::autoIndent($php, '  ', '    ');
    $aliases = CodegenUtil::aliasify($php);
    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    if ('' !== $aliases_php) {
      $aliases_php = "\n" . $aliases_php;
    }

    $php = <<<EOT
<?php
$aliases_php
class C {

  /**
   * @CfrPlugin("myPlugin", "My plugin")
   *
   * @return \\$interface
   */
  public static function create() {

    return $php;
  }
}
EOT;

    $out['codegen']['help']['#markup'] = t(
    // @todo Is it a good idea to send full HTML to t()?
      <<<EOT
<p>You can use the code below as a starting point for a custom plugin in a custom module.</p>
<p>If you do so, don't forget to:</p>
<ul>
  <li>Implement <code>hook_cfrplugin_info()</code> similar to how other modules do it.</li>
  <li>Set up a PSR-4 namespace directory structure for your class files.</li>
  <li>Replace "myPlugin", "My plugin" and "class C" with more suitable names, and put the class into a namespace.</li>
  <li>Leave the <code>@return</code> tag in place, because it tells cfrplugindiscovery about the plugin type.</li>
  <li>Fix all <code>@todo</code> items. These occur if code generation was incomplete.</li>
</ul>
EOT
    );

    $out['codegen']['code']['#children'] = UiCodeUtil::highlightPhp($php);

    return $out;
  }
}
