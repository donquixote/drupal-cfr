<?php

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptyness_Hardcoded;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface;

class SchemaToAnything_CallbackInstanceof extends SchemaToAnything_Callback {

  /**
   * @var string
   */
  private $schemaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface|null
   */
  public static function createFrom(CallbackReflectionInterface $callback) {

    $params = $callback->getReflectionParameters();

    if (1
      && [0] === array_keys($params)
      && NULL !== ($reflSchemaInterface = $params[0]->getClass())
      && $reflSchemaInterface->implementsInterface(CfSchemaInterface::class)
    ) {
      $schemaInterface = $reflSchemaInterface->getName();

      if ($schemaInterface === CfSchemaInterface::class) {
        return new parent($callback);
      }

      return new self($callback, $schemaInterface);
    }

    $args = [];
    foreach ($params as $param) {

      if (NULL === $paramReflClass = $param->getClass()) {
        return NULL;
      }

      if (SchemaToEmptynessInterface::class === $paramReflClass->getName()) {
        // @todo This is ham-fisted.
        $args[] = new SchemaToEmptyness_Hardcoded();
      }
      else {
        return NULL;
      }
    }

    $candidate = $callback->invokeArgs($args);

    if (is_callable($candidate)) {
      $callback = CallbackUtil::callableGetCallback($candidate);
      return self::createFrom($callback);
    }

    return NULL;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $schemaInterface
   */
  public function __construct(CallbackReflectionInterface $callback, $schemaInterface) {
    parent::__construct($callback);
    $this->schemaInterface = $schemaInterface;
  }

  /**
   * @return string
   */
  public function getSchemaInterface() {
    return $this->schemaInterface;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {

    if (!$schema instanceof $this->schemaInterface) {
      return NULL;
    }

    return parent::schema($schema, $interface);
  }

}
