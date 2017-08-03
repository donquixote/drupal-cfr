<?php

namespace Drupal\cfrrealm\DefinitionsByTypeAndId;

use Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;

abstract class DefinitionsByTypeAndId_HookDiscoveryBase implements DefinitionsByTypeAndIdInterface {

  /**
   * @var string
   */
  private $hook;

  /**
   * @var array
   */
  private $arguments;

  /**
   * @param string $hook
   * @param array $arguments
   */
  public function __construct($hook, array $arguments = []) {
    $this->hook = $hook;
    $this->arguments = $arguments;
  }

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId() {
    $definitions = [];
    $suffix = '_' . $this->hook;
    foreach ($this->getImplementingModules($this->hook) as $module) {
      foreach ($this->moduleGetDefinitionsByTypeAndId($module, $suffix) as $type => $definitionsById) {
        foreach ($definitionsById as $id => $definition) {
          if (!isset($definition['module'])) {
            $definition['module'] = $module;
          }
          else {
            $module = $definition['module'];
          }
          $definitions[$type][$module . '.' . $id] = $definition;
        }
      }
    }
    return $definitions;
  }

  /**
   * @param string $hook
   *
   * @return string[]
   */
  abstract protected function getImplementingModules($hook);

  /**
   * @param string $module
   * @param string $suffix
   *
   * @return array[]
   */
  private function moduleGetDefinitionsByTypeAndId($module, $suffix) {
    $function = $module . $suffix;
    if (!function_exists($function)) {
      return [];
    }
    $moduleDefinitionsByTypeAndId = call_user_func_array($function, $this->arguments);
    if (!is_array($moduleDefinitionsByTypeAndId)) {
      return [];
    }
    return $moduleDefinitionsByTypeAndId;
  }
}
