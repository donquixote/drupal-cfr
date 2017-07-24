<?php

namespace Donquixote\Cf\Emptiness;

class Emptiness_Sequence implements EmptinessInterface {

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptiness;

  /**
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptiness
   */
  public function __construct(EmptinessInterface $emptiness) {
    $this->emptiness = $emptiness;
  }

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    if (NULL === $conf || [] === $conf) {
      return TRUE;
    }
    if (!is_array($conf)) {
      // Invalid configuration.
      return FALSE;
    }
    foreach ($conf as $delta => $deltaConf) {
      if ($delta[0] === '#') {
        // Invalid delta.
        return FALSE;
      }
      if (!$this->emptiness->confIsEmpty($deltaConf)) {
        // Non-empty configuration for delta.
        return FALSE;
      }
    }
    // All items are empty.
    return TRUE;
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return [];
  }

}
