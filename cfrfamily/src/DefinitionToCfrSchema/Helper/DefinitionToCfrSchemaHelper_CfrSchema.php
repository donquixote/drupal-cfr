<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\SchemaCreationException;

class DefinitionToCfrSchemaHelper_CfrSchema implements DefinitionToCfrSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function objectGetCfrSchema($object) {

    if ($object instanceof CfSchemaInterface) {
      return $object;
    }

    throw new SchemaCreationException("Object is not a CfrSchema.");
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function factoryGetCfrSchema(CallbackReflectionInterface $factory, CfrContextInterface $context = NULL) {

    $serialArgs = [];
    foreach ($factory->getReflectionParameters() as $param) {

      // @todo Only accept optional parameters.
      if ($context && $context->paramValueExists($param)) {
        $arg = $context->paramGetValue($param);
      }
      elseif ($param->isOptional()) {
        $arg = $param->getDefaultValue();
      }
      else {
        $paramName = $param->getName();
        throw new SchemaCreationException("Leftover parameter '$paramName' for the factory callback provided.");
      }

      $serialArgs[] = $arg;
    }

    $candidate = $factory->invokeArgs($serialArgs);

    if ($candidate instanceof CfSchemaInterface) {
      return $candidate;
    }

    if (!is_object($candidate)) {
      $export = var_export($candidate, TRUE);
      throw new SchemaCreationException("The factory returned non-object value $export.");
    }

    $class = get_class($candidate);
    throw new SchemaCreationException("The factory returned a non-CfrSchema object of class $class.");
  }
}
