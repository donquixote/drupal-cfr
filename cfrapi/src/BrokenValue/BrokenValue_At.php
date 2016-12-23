<?php

namespace Drupal\cfrapi\BrokenValue;

class BrokenValue_At implements BrokenValueInterface {

  /**
   * @var \Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  private $brokenValue;

  /**
   * @var int|string
   */
  private $key;

  /**
   * @param \Drupal\cfrapi\BrokenValue\BrokenValueInterface $brokenValue
   * @param string|int $key
   */
  public function __construct(BrokenValueInterface $brokenValue, $key) {
    $this->brokenValue = $brokenValue;
    $this->key = $key;
  }

}
