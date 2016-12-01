<?php
use Drupal\cfrplugin\Hub\CfrPluginHub;

/**
 * @param string $interface
 *
 * @return string|array
 */
function _cfrplugin_report_interface_page($interface) {

  $services = CfrPluginHub::getContainer();
  $definitionToLabel = $services->definitionToLabel;
  $definitionToGroupLabel = $services->definitionToGrouplabel;

  $definitionMap = $services->typeToDefmap->typeGetDefmap($interface);

  $rows = [];
  $rows_grouped = [];
  foreach ($definitionMap->getDefinitionsById() as $key => $definition) {

    $row = [
      l(
        $definitionToLabel->definitionGetLabel($definition, $key),
        'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface) . '/plugin/' . $key),
      '<code>' . check_plain($key) . '</code>',
      '<pre>' . var_export($definition, TRUE) . '</pre>',
    ];

    if (NULL !== $groupLabelOrNull = $definitionToGroupLabel->definitionGetLabel($definition, null)) {
      $rows_grouped[$groupLabelOrNull][] = $row;
    }
    else {
      $rows_toplevel[] = $row;
    }
  }

  foreach ($rows_grouped as $groupLabel => $rowsInGroup) {
    $rows[] = [
      [
        'colspan' => 3,
        'data' => '<h3>' . check_plain($groupLabel) . '</h3>',
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
