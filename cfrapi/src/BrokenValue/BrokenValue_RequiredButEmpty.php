<?php

namespace Drupal\cfrapi\BrokenValue;

class BrokenValue_RequiredButEmpty implements BrokenValueInterface {

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param mixed $conf
   */
  public function __construct($conf) {
    $this->conf = $conf;
  }

}
