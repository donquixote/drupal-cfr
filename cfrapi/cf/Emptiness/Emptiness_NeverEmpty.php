<?php

namespace Donquixote\Cf\Emptiness;

class Emptiness_NeverEmpty implements EmptinessInterface {

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
