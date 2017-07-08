<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

interface DefinitionToCfrSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function objectGetCfrSchema($object);

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function factoryGetCfrSchema(CallbackReflectionInterface $factory, CfrContextInterface $context = NULL);

}
