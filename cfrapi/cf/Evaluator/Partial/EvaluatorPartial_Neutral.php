<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;

/**
 * @Cf
 */
class EvaluatorPartial_Neutral extends EvaluatorPartial_DecoratorBase {

  /**
   * @param \Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface $schema
   */
  public function __construct(CfSchema_SkipEvaluatorInterface $schema) {
    parent::__construct($schema->getDecorated());
  }
}
