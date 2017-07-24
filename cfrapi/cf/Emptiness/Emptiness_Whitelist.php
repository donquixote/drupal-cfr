<?php

namespace Donquixote\Cf\Emptiness;

class Emptiness_Whitelist implements EmptinessInterface {

  /**
   * @var mixed
   */
  private $defaultEmptyConf;

  /**
   * @var mixed[]
   */
  private $otherEmptyConfigurations;

  /**
   * @param mixed $defaultEmptyConf
   * @param mixed[] $otherEmptyConfigurations
   */
  public function __construct($defaultEmptyConf, array $otherEmptyConfigurations = []) {
    $this->defaultEmptyConf = $defaultEmptyConf;
    $this->otherEmptyConfigurations = $otherEmptyConfigurations;
  }

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    if ($conf === $this->defaultEmptyConf) {
      return TRUE;
    }
    // in_array() does not work here because it uses == instead of ===.
    foreach ($this->otherEmptyConfigurations as $emptyConf) {
      if ($conf === $emptyConf) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Gets a configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return $this->defaultEmptyConf;
  }
}
