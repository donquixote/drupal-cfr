<?php

use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\Util\HtmlUtil;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrplugin\Util\UiCodeUtil;
use Drupal\cfrplugin\Util\UiDefinitionUtil;
use Drupal\cfrreflection\Util\StringUtil;

/**
 * @param array $df
 *
 * @return string|array
 */
function _cfrplugin_report_definition_page(array $df) {

  $key = $df['id'];
  $interface = $df['interface'];
  $definition = $df['definition'];

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
    . l(t('plugins'), 'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface))
    . ' | '
    . l(t('code'), 'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface) . '/code'),
  ];

  $rows[] = [
    t('Label'),
    '<h3>' . $definitionToLabel->definitionGetLabel($definition, $key) . '</h3>',
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
    '<pre>' . var_export($definition, TRUE) . '</pre>',
  ];

  try {
    $schema = $definitionToSchema->definitionGetSchema($definition);
    $configurator = $schemaToConfigurator->schemaGetConfigurator($schema);

    $reflObject = new \ReflectionObject($configurator);

    $rows[] = [
      t('Configurator'),
      t(
        'Object of class !class in namespace !namespace',
        [
          '!class' => '<pre>' . HtmlUtil::sanitize($reflObject->getShortName()) . '</pre>',
          '!namespace' => '<pre>' . HtmlUtil::sanitize($reflObject->getNamespaceName()) . '</pre>',
        ]),
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
