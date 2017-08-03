<?php

namespace Drupal\cfrrealm\DefinitionsByTypeAndId;

use Drupal\Core\Extension\ModuleHandlerInterface;

class DefinitionsByTypeAndId_HookDiscoveryD8 extends DefinitionsByTypeAndId_HookDiscoveryBase {

  /**
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  private $moduleHandler;

  /**
   * @param string $hook
   * @param array $arguments
   *
   * @return self
   */
  public static function createFromGlobals($hook, array $arguments = []) {

    return new self(
      \Drupal::moduleHandler(),
      $hook,
      $arguments);
  }

  /**
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   * @param string $hook
   * @param array $arguments
   */
  public function __construct(ModuleHandlerInterface $moduleHandler, $hook, array $arguments = []) {
    parent::__construct($hook, $arguments);
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * @param string $hook
   *
   * @return string[]
   */
  protected function getImplementingModules($hook) {
    return $this->moduleHandler->getImplementations($hook);
  }
}
