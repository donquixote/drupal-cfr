<?php

namespace Drupal\cfrapi\ConfEmptyness;

class ConfEmptyness_NeverEmpty implements ConfEmptynessInterface {

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    return FALSE;
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   *
   * @throws \Exception
   */
  public function getEmptyConf() {
    throw new \Exception('Never empty.');
  }
}
