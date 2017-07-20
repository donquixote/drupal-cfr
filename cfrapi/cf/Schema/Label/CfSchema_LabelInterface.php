<?php

namespace Donquixote\Cf\Schema\Label;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_LabelInterface extends CfSchemaLocalInterface, CfSchema_DecoratorBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * @return string|null
   */
  public function getLabel();

}
