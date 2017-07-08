<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomething_CallbackInstanceof extends SchemaToSomething_Callback {

  /**
   * @var string
   */
  private $schemaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $resultInterface
   *
   * @return \Donquixote\Cf\Legacy\SchemaToSomething\SchemaToSomethingInterface|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultInterface) {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $reflInterface = $params[0]->getClass()) {
      return NULL;
    }

    $schemaInterface = $reflInterface->getName();

    if ($schemaInterface === CfSchemaInterface::class) {
      return new parent($callback, $resultInterface);
    }

    if (!is_a($schemaInterface, CfSchemaInterface::class, TRUE)) {
      return NULL;
    }

    return new self($callback, $schemaInterface, $resultInterface);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $schemaInterface
   * @param string $resultInterface
   */
  protected function __construct(CallbackReflectionInterface $callback, $schemaInterface, $resultInterface) {
    parent::__construct($callback, $resultInterface);
    $this->schemaInterface = $schemaInterface;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema) {

    if (!$schema instanceof $this->schemaInterface) {
      return NULL;
    }

    return parent::schema($schema);
  }

}
