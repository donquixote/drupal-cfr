<?php

namespace Drupal\cfrapi\ConfEmptyness;

/**
 * @see \Drupal\cfrapi\Configurator\Sequence\Configurator_SequenceTabledrag
 * @see \Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence
 */
class ConfEmptyness_Array implements ConfEmptynessInterface {

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    return NULL === $conf || [] === $conf;
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
