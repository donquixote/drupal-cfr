<?php

namespace Drupal\cfrapi\BrokenValue;

class BrokenValue_IdUnknown implements BrokenValueInterface {

  /**
   * @var int|string
   */
  private $id;

  /**
   * @param string|int $id
   */
  public function __construct($id) {
    $this->id = $id;
  }

}
