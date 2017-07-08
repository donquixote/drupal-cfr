<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * configurator, and the callback parameters represent the context.
 */
class CallbackToConfigurator_ConfiguratorFactory extends CallbackToConfiguratorBase {

  /**
   * @param mixed $configuratorCandidate
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  protected function candidateGetConfigurator($configuratorCandidate) {

    if ($configuratorCandidate instanceof ConfiguratorInterface) {
      return $configuratorCandidate;
    }
    elseif (is_object($configuratorCandidate)) {
      $export = var_export($configuratorCandidate, TRUE);
      throw new ConfiguratorCreationException("The configurator factory returned non-object value $export.");
    }
    else {
      $class = get_class($configuratorCandidate);
      throw new ConfiguratorCreationException("The configurator factory is expected to return a ConfiguratorInterface object. It returned a $class object instead.");
    }
  }
}
