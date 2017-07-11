<?php

namespace Donquixote\Cf\SchemaToSomething\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomethingPartial_CallbackInstanceofNoHelper extends SchemaToSomethingPartial_CallbackNoHelper {

  /**
   * @var string
   */
  private $schemaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $resultInterface
   *
   * @return \Donquixote\Cf\SchemaToSomething\Partial\SchemaToSomethingPartialInterface|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultInterface) {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $t0 = $params[0]->getClass()) {
      return NULL;
    }

    if (CfSchemaInterface::class === $interface = $t0->getName()) {
      return new parent($callback, $resultInterface);
    }

    if (!is_a($interface, CfSchemaInterface::class, TRUE)) {
      return NULL;
    }

    return new self($callback, $interface, $resultInterface);
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
   * @param \Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema, SchemaToSomethingHelperInterface $helper) {

    if (!$schema instanceof $this->schemaInterface) {
      return NULL;
    }

    return parent::schema($schema, $helper);
  }

}
