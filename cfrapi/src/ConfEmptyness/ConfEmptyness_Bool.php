<?php

namespace Drupal\cfrapi\ConfEmptyness;

class ConfEmptyness_Bool implements ConfEmptynessInterface {

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    return empty($conf);
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return FALSE;
  }
}
