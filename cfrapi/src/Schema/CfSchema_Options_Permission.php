<?php

namespace Drupal\cfrapi\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

/**
 * A legend to choose a permission from the Drupal permission system.
 *
 * @see \views_plugin_access_perm
 */
class CfSchema_Options_Permission implements CfSchema_OptionsInterface {

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {

    $module_info = system_get_info('module');

    // Get list of permissions
    $options = array();
    foreach (module_implements('permission') as $module) {
      $permissions = module_invoke($module, 'permission');
      foreach ($permissions as $name => $perm) {
        $options[$module_info[$module]['name']][$name] = strip_tags($perm['title']);
      }
    }

    ksort($options);

    return $options;
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

    $permissions = module_invoke_all('permission');

    if (isset($permissions[$id])) {
      return $permissions[$id]['title'];
    }

    return NULL;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    $permissions = module_invoke_all('permission');

    return isset($permissions[$id]);
  }
}
