<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * configurator, and the callback parameters represent the context.
 */
class CallbackToConfigurator_SchemaFactory implements CallbackToConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   */
  public function __construct(CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator) {
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $schemaFactoryCallback
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
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

    $schemaCandidate = $schemaFactoryCallback->invokeArgs($serialArgs);

    if (!$schemaCandidate instanceof CfrSchemaInterface) {
      return NULL;
    }

    try {
      $configuratorCandidate = $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator($schemaCandidate);
    }
    catch (\Exception $e) {
      return NULL;
    }

    if (!$configuratorCandidate instanceof ConfiguratorInterface) {
      return NULL;
    }

    return $configuratorCandidate;
  }
}
