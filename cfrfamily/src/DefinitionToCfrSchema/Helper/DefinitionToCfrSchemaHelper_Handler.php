<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_Callback;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\ValueProvider\ValueProvider_FixedValue;

class DefinitionToCfrSchemaHelper_Handler implements DefinitionToCfrSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function objectGetCfrSchema($object) {
    return new ValueProvider_FixedValue($object);
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
    return new CfSchema_Callback($factory, $context);
  }
}
