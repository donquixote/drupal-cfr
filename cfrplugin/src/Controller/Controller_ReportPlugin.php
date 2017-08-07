<?php

namespace Drupal\cfrplugin\Controller;

use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\Util\HtmlUtil;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrplugin\RouteHelper\ClassRouteHelper;
use Drupal\cfrplugin\Util\UiCodeUtil;
use Drupal\cfrplugin\Util\UiDefinitionUtil;
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
 * @Route("/admin/reports/cfrplugin/{interface}/plugin/{definition}")
 * @Cache(expires="tomorrow")
 * @RouteIsAdmin
 * @RouteTitleMethod("title")
 * @RouteRequirePermission("view cfrplugin report")
 * @RouteParameters(
 *   interface = "cfrplugin:interface",
 *   definition = "cfrplugin:definition"
 * )
 */
class Controller_ReportPlugin extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $interface
   * @param string $id
   *
   * @return \Drupal\cfrplugin\RouteHelper\ClassRouteHelperInterface
   */
  public static function route($interface, $id) {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => _cfrplugin_interface_slug($interface),
        'definition' => _cfrplugin_interface_slug($id),
      ],
      'definition');
  }

  /**
   * @param array $definition
   *
   * @return string
   */
  public function title($definition) {

    if (isset($definition['definition']['label'])) {
      return $definition['definition']['label'];
    }

    return $definition['id'];
  }

  /**
   * @Route
   * @RouteDefaultTaskLink("Definition")
   *
   * @param string $interface
   * @param array $definition
   *
   * @return array
   */
  public function definition($interface, $definition) {

    $key = $definition['id'];
    $definition = $definition['definition'];

    $services = CfrPluginHub::getContainer();
    $definitionToLabel = $services->definitionToLabel;
    $definitionToGroupLabel = $services->definitionToGrouplabel;
    $schemaToConfigurator = $services->schemaToConfigurator;

    // @todo THis should be a service.
    $definitionToSchema = DefinitionToSchema_Mappers::create();

    $rows = [];

    $rows[] = [
      t('Interface'),

      Markup::create(
        StringUtil::interfaceGenerateLabel($interface)
        . '<br/>'
        . '<code>' . HtmlUtil::sanitize($interface) . '</code>'
        . '<br/>'
        . Controller_ReportIface::route($interface)
          ->link(t('plugins'))
          ->toString()
        . ' | '
        . Controller_ReportIface::route($interface)
          ->subpage('code')
          ->link(t('code'))
          ->toString()),
    ];

    $rows[] = [
      t('Label'),

      Markup::create(
        '<h3>'
        . $definitionToLabel->definitionGetLabel($definition, $key)
        . '</h3>'),
    ];

    if (NULL !== $groupLabel = $definitionToGroupLabel->definitionGetLabel(
        $definition,
        NULL)
    ) {
      $rows[] = [
        t('Group label'),
        $groupLabel,
      ];
    }

    $rows[] = [
      t('Definition'),

      UiCodeUtil::exportHighlightWrap($definition),
    ];

    try {
      $schema = $definitionToSchema->definitionGetSchema($definition);
      $configurator = $schemaToConfigurator->schemaGetConfigurator($schema);

      $reflObject = new \ReflectionObject($configurator);


      $rows[] = [
        $this->t('Configurator class'),

        UiCodeUtil::highlightAndWrap(''
          . "namespace " . $reflObject->getNamespaceName() . ";\n"
          . "\n"
          . "class " . $reflObject->getShortName() . " .. {..}"),
      ];
    }
    catch (\Exception $e) {

      $rows[] = [
        t('Problem'),
        HtmlUtil::sanitize($e->getMessage()),
      ];
    }

    if (NULL !== $snippet = UiDefinitionUtil::definitionGetCodeSnippet(
        $definition)
    ) {
      $snippet = ''
        . '<?php'
        . "\n[..]"
        . "\n"
        . "\n"
        . $snippet;

      $rows[] = [
        t('Code snippet'),

        UiCodeUtil::highlightAndWrap($snippet, FALSE),
      ];
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
   * @param array $definition
   *
   * @return mixed
   */
  public function code($definition) {

    $definition = $definition['definition'];

    if (NULL === $class = UiDefinitionUtil::definitionGetClass($definition)) {
      return t('Cannot find a class name from the plugin definition.');
    }

    $html = UiCodeUtil::classGetCodeAsHtml($class);

    return ['#children' => $html];
  }

  /**
   * @Route("/devel")
   * @RouteTaskLink("Devel")
   *
   * @param string $interface
   * @param array $definition
   *
   * @return array
   */
  public function devel($interface, $definition) {

    $id = $definition['id'];
    $definition = $definition['definition'];

    $services = CfrPluginHub::getContainer();
    $definitionToLabel = $services->definitionToLabel;
    $definitionToGroupLabel = $services->definitionToGrouplabel;
    $schemaToConfigurator = $services->schemaToConfigurator;

    // @todo THis should be a service.
    $definitionToSchema = DefinitionToSchema_Mappers::create();

    $rows = [];

    $rows[] = [
      t('Interface'),
      StringUtil::interfaceGenerateLabel($interface)
      . '<br/>'
      . '<code>' . HtmlUtil::sanitize($interface) . '</code>'
      . '<br/>'
      . Controller_ReportIface::route($interface)
        ->link(t('plugins'))
        ->toString()
      . ' | '
      . Controller_ReportIface::route($interface)
        ->subpage('code')
        ->link(t('code'))
        ->toString()
      . '',
    ];

    $rows[] = [
      t('Label'),
      '<h3>' . $definitionToLabel->definitionGetLabel($definition, $id) . '</h3>',
    ];

    if (NULL !== $groupLabel = $definitionToGroupLabel->definitionGetLabel($definition, null)) {
      $rows[] = [
        t('Group label'),
        $groupLabel,
      ];
    }

    $rows[] = [
      t('Definition'),
      '<pre>' . var_export($definition, TRUE) . '</pre>',
    ];

    try {
      // Just check if anything blows up.
      $schema = $definitionToSchema->definitionGetSchema($definition);
      $configurator = $schemaToConfigurator->schemaGetConfigurator($schema);

      $rows[] = [
        t('Configurator'),
        UiDumpUtil::dumpValue($configurator),
      ];
    }
    catch (\Exception $e) {

      $rows = array_merge(
        $rows,
        UiDumpUtil::exceptionGetTableRows($e));
    }

    if (NULL !== $snippet = UiDefinitionUtil::definitionGetCodeSnippet($definition)) {
      $rows[] = [
        t('Code snippet'),
        UiCodeUtil::highlightPhp(''
          . '<?php'
          . "\n[..]"
          . "\n"
          . "\n"
          . $snippet),
      ];
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

}
