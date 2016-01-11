<?php

namespace Drupal\cfrfamily\ArgDefToConfigurator;

use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

class ArgDefToConfigurator_ConfiguratorObject implements ArgDefToConfiguratorInterface {

  /**
   * Gets the handler object, or a fallback object for broken / missing handler.
   *
   * @param mixed $cfr
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function argDefinitionGetConfigurator($cfr, array $definition, CfrContextInterface $context = NULL) {
    if (!$cfr instanceof ConfiguratorInterface) {
      return new BrokenConfigurator($this, get_defined_vars(), 'Not a valid configurator object.');
    }
    return $cfr;
  }
}
