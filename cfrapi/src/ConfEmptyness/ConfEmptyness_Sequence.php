<?php

namespace Drupal\cfrapi\ConfEmptyness;

/**
 * @see \Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence
 */
class ConfEmptyness_Sequence implements ConfEmptynessInterface {

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   */
  function __construct(ConfEmptynessInterface $emptyness) {
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  function confIsEmpty($conf) {
    if (NULL === $conf || array() === $conf) {
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
  function getEmptyConf() {
    return array();
  }

}
