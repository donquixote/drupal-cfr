<?php

namespace Donquixote\Cf\Evaluator\P2;

use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class EvaluatorP2_Neutral extends EvaluatorP2_DecoratorBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface|null
   */
  public static function create(CfSchema_SkipEvaluatorInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    return StaUtil::evaluatorP2($schema->getDecorated(), $schemaToAnything);
  }
}
