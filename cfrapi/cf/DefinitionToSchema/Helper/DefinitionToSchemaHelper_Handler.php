<?php

namespace Donquixote\Cf\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_Callback;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;

class DefinitionToSchemaHelper_Handler implements DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function objectGetSchema($object) {
    return new CfSchema_ValueProvider_FixedValue($object);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL) {
    return new CfSchema_Callback($factory, $context);
  }
}
