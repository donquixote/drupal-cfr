<?php

namespace Donquixote\Cf\Evaluator\P2;

interface EvaluatorP2_OptionableInterface extends EvaluatorP2Interface {

  /**
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  public function getEmptyness();

}
