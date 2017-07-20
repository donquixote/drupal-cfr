<?php

namespace Donquixote\Cf\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;
use Donquixote\Cf\Schema\CfSchemaInterface;

class DefinitionToSchemaHelper_Schema implements DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function objectGetSchema($object) {

    if ($object instanceof CfSchemaInterface) {
      return $object;
    }

    throw new CfSchemaCreationException("Object is not a CfSchema.");
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL) {

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
        throw new CfSchemaCreationException("Leftover parameter '$paramName' for the factory callback provided.");
      }

      $serialArgs[] = $arg;
    }

    $candidate = $factory->invokeArgs($serialArgs);

    if ($candidate instanceof CfSchemaInterface) {
      return $candidate;
    }

    if (!is_object($candidate)) {
      $export = var_export($candidate, TRUE);
      throw new CfSchemaCreationException("The factory returned non-object value $export.");
    }

    $class = get_class($candidate);
    throw new CfSchemaCreationException("The factory returned a non-CfSchema object of class $class.");
  }
}
