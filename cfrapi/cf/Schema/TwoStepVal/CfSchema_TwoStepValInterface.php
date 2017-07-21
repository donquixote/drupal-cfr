<?php

namespace Donquixote\Cf\Schema\TwoStepVal;

use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_TwoStepValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\TwoStep\CfSchema_TwoStepInterface
   */
  public function getDecorated();

  /**
   * @return \Donquixote\Cf\V2V\TwoStep\V2V_TwoStepInterface
   */
  public function getV2V();

}
