<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;

class SchemaToAnythingPartial_CallbackNoHelper extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string|null $schemaType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_CallbackNoHelper
   */
  public static function fromClassName($class, $schemaType = NULL) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self(
      $callback,
      $schemaType,
      $class);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $resultType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultType = NULL) {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $t0 = $params[0]->getClass()) {
      return NULL;
    }

    if (CfSchemaInterface::class === $schemaType = $t0->getName()) {
      $schemaType = NULL;
    }
    elseif (!is_a($schemaType, CfSchemaInterface::class, TRUE)) {
      return NULL;
    }

    return new self($callback, $schemaType, $resultType);
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $schemaType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($schemaType, $resultType);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schemaDoGetObject(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

    return $this->callback->invokeArgs([$schema]);
  }
}
