<?php

namespace Drupal\cfrrealm\DefinitionToConfigurator;

use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

class DefinitionToConfigurator_Proxy implements DefinitionToConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   */
  private $instance;

  /**
   * @var callable
   */
  private $factory;

  /**
   * @param callable $factory
   */
  function __construct($factory) {
    $this->factory = $factory;
  }

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function definitionGetConfigurator(array $definition, CfrContextInterface $context = NULL) {
    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }
    return $this->instance->definitionGetConfigurator($definition, $context);
  }
}
