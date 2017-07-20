<?php

namespace Donquixote\Cf\Evaluator\Partial;

interface EvaluatorPartial_OptionableInterface extends EvaluatorPartialInterface {

  /**
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  public function getEmptyness();

}
