<?php

namespace Drupal\cfrapi\ConfEmptyness;

class ConfEmptyness_Key implements ConfEmptynessInterface {

  /**
   * @var string
   */
  private $key;

  /**
   * @param string $key
   */
  public function __construct($key) {
    $this->key = $key;
  }

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    return !isset($conf[$this->key]) || '' === $conf[$this->key];
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return NULL;
  }
}
