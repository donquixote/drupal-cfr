<?php

namespace Drupal\cfrapi\ConfEmptyness;

use Donquixote\Cf\Emptyness\EmptynessInterface;

class ConfEmptyness_FromCfEmptyness implements ConfEmptynessInterface {

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
    return $this->emptyness->confIsEmpty($conf);
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return $this->emptyness->getEmptyConf();
  }
}
