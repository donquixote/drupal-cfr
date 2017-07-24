<?php

namespace Drupal\cfrapi\ConfEmptyness;

use Donquixote\Cf\Emptiness\EmptinessInterface;

class ConfEmptyness_FromCfEmptyness implements ConfEmptynessInterface {

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptyness
   */
  public function __construct(EmptinessInterface $emptyness) {
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
