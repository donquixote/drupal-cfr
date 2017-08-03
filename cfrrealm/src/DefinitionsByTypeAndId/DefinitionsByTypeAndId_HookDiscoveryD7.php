<?php

namespace Drupal\cfrrealm\DefinitionsByTypeAndId;

class DefinitionsByTypeAndId_HookDiscoveryD7 extends DefinitionsByTypeAndId_HookDiscoveryBase {

  /**
   * @param string $hook
   *
   * @return string[]
   */
  protected function getImplementingModules($hook) {
    /** @noinspection PhpUndefinedFunctionInspection */
    return module_implements($hook);
  }
}
