<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Util\ContextReflectionUtil;
use Drupal\cfrreflection\Util\CfrReflectionUtil;

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

    $serialArgs = NULL === $context
      ? CfrReflectionUtil::paramsGetArgs($configuratorFactoryCallback->getReflectionParameters())
      : ContextReflectionUtil::paramsContextGetArgs($configuratorFactoryCallback->getReflectionParameters(), $context);

    if (!is_array($serialArgs)) {
      return NULL;
    }

    $configuratorCandidate = $configuratorFactoryCallback->invokeArgs($serialArgs);

    if ($configuratorCandidate instanceof ConfiguratorInterface) {
      return $configuratorCandidate;
    }
    else {
      return NULL;
    }
  }
}
