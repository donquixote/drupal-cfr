<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

/**
 * @internal
 *
 * These are helper objects used within
 * @see \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Mappers,
 *
 */
interface DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function objectGetSchema($object);

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfrContextInterface $context = NULL);

}
