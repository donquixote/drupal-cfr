<?php

namespace Drupal\cfrapi\ConfEmptyness;

interface ConfEmptynessInterface {

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  function confIsEmpty($conf);

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  function getEmptyConf();

}
