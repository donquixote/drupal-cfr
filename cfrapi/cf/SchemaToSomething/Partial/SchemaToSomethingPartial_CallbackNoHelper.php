<?php

namespace Donquixote\Cf\SchemaToSomething\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomethingPartial_CallbackNoHelper extends SchemaToSomethingPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

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

    if (CfSchemaInterface::class !== $t0->getName()) {
      return NULL;
    }

    return new self($callback, $resultInterface);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $resultInterface
   */
  protected function __construct(CallbackReflectionInterface $callback, $resultInterface) {
    parent::__construct($resultInterface);
    $this->callback = $callback;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
   *
   * @return null|object
   * @internal param string $interface
   */
  public function schemaGetCandidate(CfSchemaInterface $schema, SchemaToSomethingHelperInterface $helper) {
    return $this->callback->invokeArgs([$schema]);
  }
}
