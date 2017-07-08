<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;

class XEvaluator_Neutral extends XEvaluator_DecoratorBase {

  /**
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   */
  public function __construct(CfSchema_NeutralInterface $schema) {
    parent::__construct($schema);
  }
}
