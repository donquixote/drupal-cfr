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
  $definitionToConfigurator = $services->definitionToConfigurator;

  $definitionMap = $services->typeToDefmap->typeGetDefmap($interface);

  $interfacePath = 'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface);

  $rows = [];
  $rows_grouped = [];
  foreach ($definitionMap->getDefinitionsById() as $key => $definition) {

    try {
      $definitionToConfigurator->definitionGetConfigurator($definition);
      $ok = TRUE;
    }
    catch (\Exception $e) {
      $ok = FALSE;
    }

    $row = [
      l(
        $definitionToLabel->definitionGetLabel($definition, $key),
        // The $key is already coming from a url fragment, so it is safe.
        $interfacePath . '/plugin/' . $key),
      '<code>' . check_plain($key) . '</code>',
      l(
        t('Demo'),
        $interfacePath . '/demo',
        [
          'query' => [
            'plugin[id]' => $key,
            'noshow' => TRUE,
          ],
        ]),
      $ok ? t('') : t('Broken'),
      '<pre>' . var_export($definition, TRUE) . '</pre>',
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
