<?php
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrreflection\Util\StringUtil;

/**
 * Page callback.
 *
 * @return string|array
 */
function _cfrplugin_report_page() {

  $services = CfrPluginHub::getContainer();
  $definitionToLabel = $services->definitionToLabel;
  $definitionToGroupLabel = $services->definitionToGrouplabel;

  $definitions = $services->definitionsByTypeAndId->getDefinitionsByTypeAndId();

  $rows = array();
  foreach ($definitions as $interface => $interface_definitions) {
    $interface_label = _cfrplugin_interface_get_title($interface);
    $interface_label = '<h2>' . $interface_label . '</h2><code>' . $interface . '</code>';
    $rows[] = array(
      array(
        'data' => $interface_label,
        'colspan' => 2,
      ),
    );
    foreach ($interface_definitions as $key => $definition) {
      $label = '<strong>' . $definitionToLabel->definitionGetLabel($definition, $key) . '</strong>';
      if (NULL !== $groupLabel = $definitionToGroupLabel->definitionGetLabel($definition, null)) {
        $label = '(' . $groupLabel . ')<br/>' . $label;
      }
      $label .= '<br/><code>' . $key . '</code>';
      $rows[] = array(
        $label,
        '<pre>' . var_export($definition, TRUE) . '</pre>',
      );
    }
  }

  return array(
    /* @see theme_table() */
    '#theme' => 'table',
    '#rows' => $rows,
  );
}

/**
 * @param string $interface
 *
 * @return string
 */
function _cfrplugin_interface_get_title($interface) {
  $title = $interface;
  if (FALSE !== $pos = strrpos($title, '\\')) {
    $title = substr($title, $pos + 1);
  }
  if ('Interface' === substr($title, -9) && 'Interface' !== $title) {
    $title = substr($title, 0, -9);
  }
  return StringUtil::methodNameGenerateLabel($title);
}
