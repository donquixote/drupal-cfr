<?php

namespace Drupal\cfrapi\BrokenValue;

class BrokenValue_IncompatibleConfiguration implements BrokenValueInterface {

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @var string
   */
  private $message;

  /**
   * @param mixed $conf
   * @param string $message
   */
  public function __construct($conf, $message) {
    $this->conf = $conf;
    $this->message = $message;
  }

}
