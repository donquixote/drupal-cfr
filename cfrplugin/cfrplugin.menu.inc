<?php
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrplugin\Util\UiUtil;
use Drupal\cfrreflection\Util\StringUtil;

/**
 * Implements hook_menu().
 */
function cfrplugin_menu() {
  $items = [];

  $items[$base = 'admin/reports/cfrplugin'] = [
    'type' => MENU_NORMAL_ITEM,
    /* @see cfrplugin_permission() */
    'access arguments' => [$perm = 'view cfrplugin report'],
    'title' => 'Cfr plugins',
    /* @see _cfrplugin_report_overview_page() */
    'page callback' => '_cfrplugin_report_overview_page',
    'file' => 'cfrplugin.page.report.inc',
  ];

  /* @see _cfrplugin_interface_load() */
  $items[$interface_base = $base . '/%_cfrplugin_interface'] = [
    'type' => MENU_NORMAL_ITEM,
    'access arguments' => [$perm],
    /* @see _cfrplugin_report_interface_page_title() */
    'title callback' => '_cfrplugin_report_interface_page_title',
    'title arguments' => [3],
    /* @see _cfrplugin_report_interface_page() */
    'page callback' => '_cfrplugin_report_interface_page',
    'page arguments' => [3],
    'file' => 'cfrplugin.page.report.interface.inc',
  ];

  $items[$interface_base . '/list'] = [
    'type' => MENU_DEFAULT_LOCAL_TASK,
    'title' => 'List of plugins',
    'weight' => -10,
  ];

  $items[$interface_base . '/code'] = [
    'type' => MENU_LOCAL_TASK,
    'access arguments' => [$perm],
    'title' => 'View code',
    /* @see _cfrplugin_report_interface_code_page() */
    'page callback' => '_cfrplugin_report_interface_code_page',
    'page arguments' => [3],
    'file' => 'cfrplugin.page.report.interface.code.inc',
  ];

  /* @see _cfrplugin_definition_load() */
  $items[$interface_base . '/plugin/%_cfrplugin_definition'] = [
    'type' => MENU_NORMAL_ITEM,
    'load arguments' => [3],
    'access arguments' => [$perm],
    /* @see _cfrplugin_definition_page_title() */
    'title callback' => '_cfrplugin_definition_page_title',
    'title arguments' => [5],
    /* @see _cfrplugin_report_definition_page() */
    'page callback' => '_cfrplugin_report_definition_page',
    'page arguments' => [5],
    'file' => 'cfrplugin.page.report.definition.inc',
  ];

  return $items;
}

/**
 * Menu wildcard loader for '%_cfrplugin_interface'
 *
 * @param string $arg
 *
 * @return mixed
 */
function _cfrplugin_interface_load($arg) {
  $interface = str_replace('.', '\\', $arg);
  if (!UiUtil::interfaceNameIsValid($interface)) {
    return NULL;
  }
  // At this point, $interface looks like a valid class name. But it could still
  // be a non-existing interface, and possibly something ridiculously long.
  // Avoid interface_exists(), because autoload can have side effects.
  return $interface;
}

/**
 * @param string $interface
 *
 * @return mixed
 */
function _cfrplugin_interface_slug($interface) {
  return str_replace('\\', '.', $interface);
}

/**
 * @param string $interface
 *
 * @return string
 */
function _cfrplugin_report_interface_page_title($interface) {
  return StringUtil::interfaceGenerateLabel($interface);
}

/**
 * @param string $arg
 * @param string $interface_arg
 *
 * @return array
 */
function _cfrplugin_definition_load($arg, $interface_arg) {

  if (NULL === $interface = _cfrplugin_interface_load($interface_arg)) {
    return NULL;
  }

  if (NULL === $definition = CfrPluginHub::getContainer()->typeToDefmap->typeGetDefmap($interface)->idGetDefinition($arg)) {
    return NULL;
  }

  return [
    'interface' => $interface,
    'id' => $arg,
    'definition' => $definition,
  ];
}

/**
 * @param array $df
 *
 * @return string
 */
function _cfrplugin_definition_page_title(array $df) {

  if (isset($df['definition']['label'])) {
    return $df['definition']['label'];
  }

  return $df['id'];
}