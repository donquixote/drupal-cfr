<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Unconfigurable\Configurator_FixedValue;
use Drupal\cfrapi\Context\CfrContextInterface;

/**
 * Creates a configurator for a callback with an unknown return value.
 */
class CallbackToConfigurator_UndesignatedCallback implements CallbackToConfiguratorInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function callbackGetConfigurator(CallbackReflectionInterface $callback, CfrContextInterface $context = NULL) {

    // @todo Test if can be called without arguments.
    $configuratorOrValue = $callback->invokeArgs([]);

    if ($configuratorOrValue instanceof ConfiguratorInterface) {
      return $configuratorOrValue;
    }
    else {
      return new Configurator_FixedValue($configuratorOrValue);
    }
  }
}
