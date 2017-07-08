<?php

namespace Donquixote\Cf\Legacy\Evaluator;

interface LegacyEvaluator_OptionableInterface extends LegacyEvaluatorInterface {

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   */
  public function getEmptyness();

}
