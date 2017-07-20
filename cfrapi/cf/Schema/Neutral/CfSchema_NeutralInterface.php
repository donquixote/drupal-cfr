<?php

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_NeutralInterface extends CfSchema_TransformableInterface, CfSchema_ValueToValueBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

}
