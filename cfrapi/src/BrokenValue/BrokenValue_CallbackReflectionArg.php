<?php

namespace Drupal\cfrapi\BrokenValue;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class BrokenValue_CallbackReflectionArg implements BrokenValueInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @var array
   */
  private $args;

  /**
   * @var int
   */
  private $i;

  /**
   * @var string
   */
  private $message;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param array $args
   * @param int $i
   * @param string $message
   */
  public function __construct(CallbackReflectionInterface $callbackReflection, array $args, $i, $message) {
    $this->callbackReflection = $callbackReflection;
    $this->args = $args;
    $this->i = $i;
    $this->message = $message;
  }

}
