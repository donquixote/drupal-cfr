<?php

namespace Drupal\cfrapi\BrokenValue;

use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

class BrokenValue_MisbehavingConfToValue implements BrokenValueInterface {

  /**
   * @var \Drupal\cfrapi\ConfToValue\ConfToValueInterface
   */
  private $configurator;

  /**
   * @var string
   */
  private $message;

  /**
   * @param \Drupal\cfrapi\ConfToValue\ConfToValueInterface $configurator
   * @param string $message
   */
  public function __construct(ConfToValueInterface $configurator, $message) {
    $this->configurator = $configurator;
    $this->message = $message;
  }

}
