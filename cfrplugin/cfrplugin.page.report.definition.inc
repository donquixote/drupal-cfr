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

  return [
    '#theme' => 'table',
    '#rows' => $rows,
  ];
}
