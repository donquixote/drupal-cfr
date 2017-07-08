<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnythingPartial_CallbackInstanceofNoHelper extends SchemaToAnythingPartial_CallbackNoHelper {

  /**
   * @var string
   */
  private $interface;

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

    if (CfSchemaInterface::class === $interface = $t0->getName()) {
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
  protected function __construct(CallbackReflectionInterface $callback, $interface) {
    parent::__construct($callback);
    $this->interface = $interface;
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

    if (!$schema instanceof $this->interface) {
      return NULL;
    }

    return parent::schema($schema, $interface, $helper);
  }

}
