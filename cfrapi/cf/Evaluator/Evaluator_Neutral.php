<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;

class Evaluator_Neutral extends Evaluator_DecoratorBase {

  /**
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   */
  public function __construct(CfSchema_NeutralInterface $schema) {
    parent::__construct($schema);
  }
}
