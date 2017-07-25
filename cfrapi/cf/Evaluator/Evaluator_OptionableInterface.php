<?php

namespace Donquixote\Cf\Evaluator;

interface Evaluator_OptionableInterface extends EvaluatorInterface {

  /**
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface|null
   */
  public function getEmptiness();

}
