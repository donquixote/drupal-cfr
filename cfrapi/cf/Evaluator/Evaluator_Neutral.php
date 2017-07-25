<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class Evaluator_Neutral extends Evaluator_DecoratorBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface|null
   */
  public static function create(CfSchema_SkipEvaluatorInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    return StaUtil::evaluator($schema->getDecorated(), $schemaToAnything);
  }
}
