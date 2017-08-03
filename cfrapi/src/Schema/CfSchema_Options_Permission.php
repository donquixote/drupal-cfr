<?php

namespace Drupal\cfrapi\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\user\PermissionHandlerInterface;

/**
 * A legend to choose a permission from the Drupal permission system.
 *
 * @see \views_plugin_access_perm
 */
class CfSchema_Options_Permission implements CfSchema_OptionsInterface {

  /**
   * @var \Drupal\user\PermissionHandlerInterface
   */
  private $permissionHandler;

  /**
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  private $moduleHandler;

  /**
   * @param \Drupal\user\PermissionHandlerInterface $permissionHandler
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   */
  public function __construct(
    PermissionHandlerInterface $permissionHandler,
    ModuleHandlerInterface $moduleHandler
  ) {
    $this->permissionHandler = $permissionHandler;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   *
   * @see \Drupal\user\Form\UserPermissionsForm::buildForm()
   */
  public function getGroupedOptions() {

    $options_raw = [];
    foreach ($this->permissionHandler->getPermissions() as $key => $permission) {
      $options_raw[$permission['provider']][$key] = strip_tags($permission['title']);
    }

    // Get list of permissions
    $options = array();
    foreach ($options_raw as $provider => $provider_options) {
      $group_label = $this->moduleHandler->getName($provider);
      // Theoretically, two modules could have the same human name.
      if (isset($options[$group_label])) {
        $options[$group_label] += $provider_options;
      }
      else {
        $options[$group_label] = $provider_options;
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

    $permissions = $this->permissionHandler->getPermissions();

    if (isset($permissions[$id])) {
      // @todo Sanitize?
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

    $permissions = $this->permissionHandler->getPermissions();

    return isset($permissions[$id]);
  }
}
