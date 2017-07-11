<?php

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnything_CallbackInstanceof extends SchemaToAnything_Callback {

  /**
   * @var string
   */
  private $interface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface|null
   */
  public static function createFrom(CallbackReflectionInterface $callback) {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $reflInterface = $params[0]->getClass()) {
      return NULL;
    }

    $interface = $reflInterface->getName();

    if ($interface === CfSchemaInterface::class) {
      return new parent($callback);
    }

    if (!is_a($interface, CfSchemaInterface::class, TRUE)) {
      return NULL;
    }

    return new self($callback, $interface);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $interface
   */
  public function __construct(CallbackReflectionInterface $callback, $interface) {
    parent::__construct($callback);
    $this->interface = $interface;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {

    if (!$schema instanceof $this->interface) {
      return NULL;
    }

    return parent::schema($schema, $interface);
  }

}
