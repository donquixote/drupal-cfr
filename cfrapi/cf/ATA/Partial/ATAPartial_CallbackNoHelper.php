<?php

namespace Donquixote\Cf\ATA\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\ATA\ATAInterface;

class ATAPartial_CallbackNoHelper extends ATAPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string|null $sourceType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartial_CallbackNoHelper
   */
  public static function fromClassName($class, $sourceType = NULL) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self(
      $callback,
      $sourceType,
      $class);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $resultType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultType = NULL) {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $t0 = $params[0]->getClass()) {
      return NULL;
    }

    $sourceType = $t0->getName();

    return new self($callback, $sourceType, $resultType);
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $sourceType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($sourceType, $resultType);
  }

  /**
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Cf\ATA\ATAInterface $helper
   *
   * @return null|object An instance of $interface, or NULL.
   * An instance of $interface, or NULL.
   */
  public function doCast(
    $source,
    $interface,
    ATAInterface $helper
  ) {

    return $this->callback->invokeArgs([$source]);
  }
}
