<?php

namespace Donquixote\Cf\Emptyness;

class Emptyness_Sequence implements EmptynessInterface {

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   */
  public function __construct(EmptynessInterface $emptyness) {
    $this->emptyness = $emptyness;
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
      if (!$this->emptyness->confIsEmpty($deltaConf)) {
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
