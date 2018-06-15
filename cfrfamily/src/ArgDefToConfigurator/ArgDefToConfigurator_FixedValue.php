<?php

namespace Drupal\cfrfamily\ArgDefToConfigurator;

use Drupal\cfrapi\Configurator\Unconfigurable\Configurator_FixedValue;
use Drupal\cfrapi\Context\CfrContextInterface;

class ArgDefToConfigurator_FixedValue implements ArgDefToConfiguratorInterface {

  /**
   * @param mixed $fixedValue
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\Unconfigurable\Configurator_FixedValue|null
   */
  public function argDefinitionGetConfigurator($fixedValue, array $definition, CfrContextInterface $context = NULL) {
    if (!\is_object($fixedValue)) {
      return NULL;
    }
    return new Configurator_FixedValue($fixedValue);
  }
}
