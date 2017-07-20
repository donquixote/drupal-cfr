<?php

namespace Donquixote\Cf\Schema\SkipEvaluator;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

/**
 * Base interface for all schema decorators that have no effect on evaluators.
 */
interface CfSchema_SkipEvaluatorInterface extends CfSchema_DecoratorBaseInterface, CfSchemaLocalInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

}
