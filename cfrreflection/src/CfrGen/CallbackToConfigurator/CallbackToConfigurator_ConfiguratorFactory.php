<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\Exception\DefinitionToConfiguratorException;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * configurator, and the callback parameters represent the context.
 */
class CallbackToConfigurator_ConfiguratorFactory implements CallbackToConfiguratorInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $configuratorFactoryCallback
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrfamily\Exception\DefinitionToConfiguratorException
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
        $paramName = $param->getName();
        throw new DefinitionToConfiguratorException("Leftover parameter '$paramName' for the configurator factory callback provided.");
      }

      $serialArgs[] = $arg;
    }

    $configuratorCandidate = $configuratorFactoryCallback->invokeArgs($serialArgs);

    if ($configuratorCandidate instanceof ConfiguratorInterface) {
      return $configuratorCandidate;
    }
    elseif (is_object($configuratorCandidate)) {
      $export = var_export($configuratorCandidate, TRUE);
      throw new DefinitionToConfiguratorException("The configurator factory returned non-object value $export.");
    }
    else {
      $class = get_class($configuratorCandidate);
      throw new DefinitionToConfiguratorException("The configurator factory is expected to return a ConfiguratorInterface object. It returned a $class object instead.");
    }
  }
}
