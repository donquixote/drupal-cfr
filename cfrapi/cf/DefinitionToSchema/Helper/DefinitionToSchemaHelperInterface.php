<?php

namespace Donquixote\Cf\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;

/**
 * @internal
 *
 * These are helper objects used within
 * @see \Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers,
 *
 */
interface DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function objectGetSchema($object);

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL);

}
