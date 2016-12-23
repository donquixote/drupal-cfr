<?php

namespace Drupal\cfrapi\BrokenValue;

class BrokenValue_Exception implements BrokenValueInterface {

  /**
   * @var \Exception
   */
  private $exception;

  /**
   * @var string
   */
  private $context_message;

  /**
   * @param \Exception $exception
   * @param string $context_message
   */
  public function __construct(\Exception $exception, $context_message) {
    $this->exception = $exception;
    $this->context_message = $context_message;
  }

}
