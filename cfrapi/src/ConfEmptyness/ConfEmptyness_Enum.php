<?php

namespace Drupal\cfrapi\ConfEmptyness;

/**
 * @see \Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect
 */
class ConfEmptyness_Enum implements ConfEmptynessInterface {

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  function confIsEmpty($conf) {
    return NULL === $conf || '' === $conf;
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  function getEmptyConf() {
    return NULL;
  }
}
