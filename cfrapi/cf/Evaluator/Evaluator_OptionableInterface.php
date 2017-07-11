<?php

namespace Donquixote\Cf\Evaluator;

interface Evaluator_OptionableInterface extends EvaluatorInterface {

  /**
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  public function getEmptyness();

}
