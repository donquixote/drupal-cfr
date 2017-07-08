<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnything_Callback implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  protected function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {

    $candidate = $this->callback->invokeArgs([$schema]);

    if (NULL === $candidate) {
      return NULL;
    }

    if (!$candidate instanceof $interface) {
      return NULL;
    }

    return $candidate;
  }
}
