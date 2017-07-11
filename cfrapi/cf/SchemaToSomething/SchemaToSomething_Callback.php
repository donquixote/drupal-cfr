<?php

namespace Donquixote\Cf\SchemaToSomething;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomething_Callback extends SchemaToSomethingBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $resultInterface
   */
  protected function __construct(CallbackReflectionInterface $callback, $resultInterface) {
    parent::__construct($resultInterface);
    $this->callback = $callback;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  protected function schemaGetCandidate(CfSchemaInterface $schema) {
    return $this->callback->invokeArgs([$schema]);
  }
}
