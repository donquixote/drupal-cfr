<?php

namespace Drupal\cfrfamily\ArgDefToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

/**
 * Sub-component for DefinitionToConfigurator*.
 *
 * Implementations represent specific ways that a definition can specify a
 * configurator, and are registered for specific keys within the definition.
 *
 * @see \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
 */
interface ArgDefToConfiguratorInterface {

  /**
   * @param mixed $arg
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function argDefinitionGetConfigurator($arg, array $definition, CfrContextInterface $context = NULL);
}
