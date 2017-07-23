<?php

use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrreflection\Util\StringUtil;

/**
 * Page callback.
 *
 * @return string|array
 */
function _cfrplugin_report_overview_page() {

  $services = CfrPluginHub::getContainer();
  $definitions = $services->definitionsByTypeAndId->getDefinitionsByTypeAndId();

  $schemaToConfigurator = $services->schemaToConfigurator;

  // @todo THis should be a service.
  $definitionToSchema = DefinitionToSchema_Mappers::create();

  $rows = [];
  $rows_grouped = [];
  foreach ($definitions as $interface => $interface_definitions) {

    $label = StringUtil::interfaceGenerateLabel($interface);
    $count = t('@n plugin definitions', ['@n' => count($interface_definitions)]);
    $interface_shortname = StringUtil::classGetShortname($interface);

    $brokenIds = [];
    foreach ($interface_definitions as $id => $definition) {

      try {
        // Just check if anything blows up.
        $schema = $definitionToSchema->definitionGetSchema($definition);
        $schemaToConfigurator->schemaGetConfigurator($schema);
      }
      catch (\Exception $e) {
        $brokenIds[] = $id;
        break;
      }
    }

    if ([] !== $brokenIds) {
      $nBroken = count($brokenIds);
      $label = "($nBroken broken) $label";
    }

    $path = 'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface);

    $row = [
      $label,
      l($count, $path),
      l($interface_shortname, $path . '/code'),
      '<code>' . $interface . '</code>',
    ];

    $fragments = explode('\\', $interface);
    if (1
      && 'Drupal' === $fragments[0]
      && isset($fragments[2])
      && module_exists($fragments[1])
    ) {
      $rows_grouped[$fragments[1]][] = $row;
    }
    else {
      $rows[] = $row;
    }

    if ([] !== $brokenIds) {
      break;
    }
  }

  $modules_info = system_get_info('module_enabled');

  foreach ($rows_grouped as $module => $module_rows) {

    $module_label = isset($modules_info[$module])
      ? $modules_info[$module]['name']
      : $module;

    $rows[] = [
      [
        'colspan' => 4,
        'data' => '<h3>' . $module_label . '</h3>',
      ],
    ];

    foreach ($module_rows as $row) {
      $rows[] = $row;
    }
  }

  return [
    '#header' => [
      t('Human name'),
      t('List'),
      t('Code'),
      t('Interface'),
    ],
    /* @see theme_table() */
    '#theme' => 'table',
    '#rows' => $rows,
  ];
}
