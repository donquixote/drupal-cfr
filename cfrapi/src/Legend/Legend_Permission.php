<?php

namespace Drupal\cfrapi\Legend;

/**
 * A legend to choose a permission from the Drupal permission system.
 *
 * @see \views_plugin_access_perm
 */
class Legend_Permission implements LegendInterface {

  /**
   * @return mixed[]
   */
  public function getSelectOptions() {

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

    return '(' . t('unknown') . ') ' . $id;
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
