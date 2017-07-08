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
   *   A specific value from the plugin definition.
   * @param array $definition
   *   The entire plugin definition.
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  public function argDefinitionGetConfigurator($arg, array $definition, CfrContextInterface $context = NULL);
}
