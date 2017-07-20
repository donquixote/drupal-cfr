<?php

namespace Drupal\cfrfamily\DefinitionToLabel;

use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;

class DefinitionToLabel_FromModuleName implements DefinitionToLabelInterface {

  /**
   * @var string[]
   */
  private $labelsByModule = [];

  /**
   * @var array[]|null
   */
  private $modulesInfo;

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return string|null
   */
  public function definitionGetLabel(array $definition, $else) {
    if (isset($definition['group_label'])) {
      return $definition['group_label'];
    }
    return isset($definition['module'])
      ? $this->moduleGetLabel($definition['module'])
      : $else;
  }

  /**
   * @param string $module
   *
   * @return string
   */
  private function moduleGetLabel($module) {
    return array_key_exists($module, $this->labelsByModule)
      ? $this->labelsByModule[$module]
      : $this->labelsByModule[$module] = $this->moduleFindLabel($module);
  }

  /**
   * @param string $module
   *
   * @return string
   */
  private function moduleFindLabel($module) {
    if (NULL === $this->modulesInfo) {
      $this->modulesInfo = system_get_info('module_enabled');
    }
    return isset($this->modulesInfo[$module]['name'])
      ? $this->modulesInfo[$module]['name']
      : $module;
  }
}
