<?php

namespace Drupal\cfrfamily\ArgDefToConfigurator;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;

class ArgDefToConfigurator_ConfiguratorObject implements ArgDefToConfiguratorInterface {

  /**
   * Gets the handler object, or a fallback object for broken / missing handler.
   *
   * @param mixed $cfr
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  public function argDefinitionGetConfigurator($cfr, array $definition, CfrContextInterface $context = NULL) {
    if (!$cfr instanceof ConfiguratorInterface) {
      if (!is_object($cfr)) {
        $cfrExport = var_export($cfr, TRUE);
        throw new ConfiguratorCreationException("The value $cfrExport is not an object.");
      }
      else {
        $class = get_class($cfr);
        throw new ConfiguratorCreationException("Expected a ConfiguratorInterface object, found a $class object.");
      }
    }
    return $cfr;
  }
}
