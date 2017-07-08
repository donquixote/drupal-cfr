<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnythingPartial_CallbackNoHelper implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\Cf\Legacy\SchemaToAnything\Partial\SchemaToAnythingPartialInterface|null
   */
  public static function create(CallbackReflectionInterface $callback) {

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

    return new self($callback);
  }

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
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

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
