<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

interface XEvaluator_OptionableInterface extends XEvaluatorInterface {

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   */
  public function getEmptyness();

}
