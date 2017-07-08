<?php

namespace Drupal\cfrreflection\FactoryToCfrSchema;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

interface FactoryToCfrSchemaInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function factoryGetCfrSchema(CallbackReflectionInterface $factory, CfrContextInterface $context) : CfSchemaInterface;

}
