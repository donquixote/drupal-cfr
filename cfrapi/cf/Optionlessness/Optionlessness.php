<?php

namespace Donquixote\Cf\Optionlessness;

class Optionlessness implements OptionlessnessInterface {

  /**
   * @var bool
   */
  private $optionless;

  /**
   * @param bool $optionless
   */
  public function __construct($optionless) {
    $this->optionless = $optionless;
  }

  /**
   * @return bool
   */
  public function isOptionless() {
    return $this->optionless;
  }
}
