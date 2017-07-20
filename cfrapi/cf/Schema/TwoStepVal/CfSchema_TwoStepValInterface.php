<?php

namespace Donquixote\Cf\Schema\TwoStepVal;

use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_TwoStepValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\TwoStep\CfSchema_TwoStepInterface
   */
  public function getDecorated();

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   * @param mixed $secondStepValue
   *   Value from the second step of configuration.
   *
   * @return mixed
   *   The final value.
   */
  public function valuesGetValue($firstStepValue, $secondStepValue);

}
