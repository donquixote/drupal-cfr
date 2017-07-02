<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

abstract class CallbackToConfiguratorBase implements CallbackToConfiguratorInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $schemaFactoryCallback
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrfamily\Exception\DefinitionToConfiguratorException
   */
  public function callbackGetConfigurator(CallbackReflectionInterface $schemaFactoryCallback, CfrContextInterface $context = NULL) {

    $serialArgs = [];
    foreach ($schemaFactoryCallback->getReflectionParameters() as $i => $param) {

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

    $candidate = $schemaFactoryCallback->invokeArgs($serialArgs);

    return $this->candidateGetConfigurator($candidate);
  }

  /**
   * @param mixed $candidate
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrfamily\Exception\DefinitionToConfiguratorException
   */
  abstract protected function candidateGetConfigurator($candidate);
}
