<?php

namespace Donquixote\Cf\Legacy\Emptyness;

/**
 * @todo Emptyness is a stupid name for this.
 */
interface EmptynessInterface {

  /**
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface
   */
  public function getEvaluator();

  /**
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf);

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf();

}
