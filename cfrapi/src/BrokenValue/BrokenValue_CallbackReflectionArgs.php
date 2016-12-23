<?php

namespace Drupal\cfrapi\BrokenValue;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class BrokenValue_CallbackReflectionArgs implements BrokenValueInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var mixed
   */
  private $args;

  /**
   * @var string
   */
  private $message;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param mixed $args
   * @param string $message
   */
  public function __construct(CallbackReflectionInterface $callback, $args, $message) {
    $this->callback = $callback;
    $this->args = $args;
    $this->message = $message;
  }

}
