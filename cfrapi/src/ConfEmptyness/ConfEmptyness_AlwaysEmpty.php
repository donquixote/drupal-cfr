<?php

namespace Drupal\cfrapi\ConfEmptyness;

class ConfEmptyness_AlwaysEmpty implements ConfEmptynessInterface {

  /**
   * @var mixed|null
   */
  private $emptyConf;

  /**
   * @param mixed $emptyConf
   */
  public function __construct($emptyConf = NULL) {
    $this->emptyConf = $emptyConf;
  }

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    return TRUE;
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return $this->emptyConf;
  }
}
