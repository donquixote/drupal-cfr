<?php

namespace Drupal\cfrfamily\ArgDefToConfigurator;

use Drupal\cfrapi\Configurator\Unconfigurable\Configurator_FixedValue;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;

class ArgDefToConfigurator_FixedValue implements ArgDefToConfiguratorInterface {

  /**
   * @param mixed $fixedValue
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  public function argDefinitionGetConfigurator($fixedValue, array $definition, CfrContextInterface $context = NULL) {
    if (!is_object($fixedValue)) {
      throw new ConfiguratorCreationException("The value is not an object.");
    }
    return new Configurator_FixedValue($fixedValue);
  }
}
