<?php

namespace Drupal\cfrapi\Exception;

class ConfToValueException extends \Exception {

  /**
   * @param mixed $conf
   * @param string $message
   * @param \Exception|null $previous
   */
  public function __construct($conf, $message = '', \Exception $previous = NULL) {
    parent::__construct($message, 0, $previous);
  }

}
