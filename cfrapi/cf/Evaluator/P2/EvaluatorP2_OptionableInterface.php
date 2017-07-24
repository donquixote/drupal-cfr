<?php

namespace Donquixote\Cf\Evaluator\P2;

interface EvaluatorP2_OptionableInterface extends EvaluatorP2Interface {

  /**
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface|null
   */
  public function getEmptiness();

}
