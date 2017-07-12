<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_Callback;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\ValueProvider\ValueProvider_FixedValue;

class DefinitionToSchemaHelper_Handler implements DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function objectGetSchema($object) {
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
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfrContextInterface $context = NULL) {
    return new CfSchema_Callback($factory, $context);
  }
}
