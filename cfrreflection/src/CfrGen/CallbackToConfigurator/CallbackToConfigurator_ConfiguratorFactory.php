<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
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
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   *
   * @return \Drupal\cfrapi\Configurator\Broken\BrokenConfigurator|null|object
   */
  public function callbackGetConfigurator(CallbackReflectionInterface $configuratorFactoryCallback, CfrContextInterface $context = NULL) {

    $serialArgs = NULL === $context
      ? CfrReflectionUtil::paramsGetArgs($configuratorFactoryCallback->getReflectionParameters())
      : ContextReflectionUtil::paramsContextGetArgs($configuratorFactoryCallback->getReflectionParameters(), $context);

    if (!is_array($serialArgs)) {
      return new BrokenConfigurator($this, get_defined_vars(), 'Insufficient context.');
    }

    $configuratorCandidate = $configuratorFactoryCallback->invokeArgs($serialArgs);

    if ($configuratorCandidate instanceof ConfiguratorInterface) {
      return $configuratorCandidate;
    }
    else {
      return new BrokenConfigurator($this, get_defined_vars(), 'Callback did not return a configurator.');
    }
  }
}
