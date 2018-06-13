<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * configurator, and the callback parameters represent the context.
 */
class CallbackToConfigurator_ConfiguratorFactory implements CallbackToConfiguratorInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $configuratorFactoryCallback
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function callbackGetConfigurator(CallbackReflectionInterface $configuratorFactoryCallback, CfrContextInterface $context = NULL) {

    $serialArgs = [];
    foreach ($configuratorFactoryCallback->getReflectionParameters() as $i => $param) {

      // @todo Only accept optional parameters.
      if ($context && $context->paramValueExists($param)) {
        $arg = $context->paramGetValue($param);
      }
      elseif ($param->isOptional()) {
        $arg = $param->getDefaultValue();
      }
      else {
        return NULL;
      }

      $serialArgs[] = $arg;
    }

    $configuratorCandidate = $configuratorFactoryCallback->invokeArgs($serialArgs);

    if ($configuratorCandidate instanceof ConfiguratorInterface) {
      return $configuratorCandidate;
    }

    return NULL;
  }
}
