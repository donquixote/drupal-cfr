<?php

namespace Drupal\cfrfamily\ArgDefToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

/**
 * @see \Drupal\cfrapi\CfrGen\DefinitionToConfigurator\DefinitionToConfiguratorInterface
 */
interface ArgDefToConfiguratorInterface {

  /**
   * @param mixed $arg
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function argDefinitionGetConfigurator($arg, array $definition, CfrContextInterface $context = NULL);
}
