<?php
use Drupal\cfrplugin\Hub\CfrPluginHub;
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
  $definitionToConfigurator = $services->definitionToConfigurator;

  $rows = [];

  $rows[] = [
    t('Interface'),
    StringUtil::interfaceGenerateLabel($interface)
      . '<br/>'
      . '<code>' . check_plain($interface) . '</code>'
      . '<br/>'
    . l(t('plugins'), 'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface))
    . ' | '
    . l(t('code'), 'admin/reports/cfrplugin/' . _cfrplugin_interface_slug($interface) . '/code'),
  ];

  $rows[] = [
    t('Label'),
    '<h3>' . $definitionToLabel->definitionGetLabel($definition, $key) . '</h3>',
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

  $values_to_dump = [];

  try {
    $configurator = $definitionToConfigurator->definitionGetConfigurator($definition);

    $values_to_dump['configurator'] = $configurator;
  }
  catch (\Exception $e) {
    $values_to_dump['exception'] = $e;
  }

  $dumped = _cfrplugin_report_dump_values($values_to_dump);

  foreach ([
    'configurator' => t('Configurator'),
    'exception' => t('Exception'),
    'no_devel' => '',
  ] as $key => $label) {

    if (!isset($dumped[$key])) {
      continue;
    }

    $rows[] = [
      t('Configurator'),
      $dumped[$key],
    ];
  }

  return [
    '#theme' => 'table',
    '#rows' => $rows,
  ];
}

/**
 * @param mixed[] $values_to_dump
 *
 * @return string[]
 */
function _cfrplugin_report_dump_values(array $values_to_dump) {

  $dumped = [];
  if (function_exists('Drupal\krumong\dpm')) {
    foreach ($values_to_dump as $k => $v) {
      if (NULL === $v) {
        $dumped[$k] = 'NULL';
      }
      else {
        $dumped[$k] = krumong()->dump($v);
      }
    }
  }
  elseif (function_exists('kdevel_print_object')) {
    foreach ($values_to_dump as $k => $v) {
      $dumped[$k] = kdevel_print_object($v);
    }
  }
  else {
    $dumped['no_devel'] = t('Devel is not installed.');
  }

  return $dumped;
}
