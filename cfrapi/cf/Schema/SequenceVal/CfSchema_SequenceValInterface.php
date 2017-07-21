<?php

namespace Donquixote\Cf\Schema\SequenceVal;

use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_SequenceValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  public function getDecorated();

  /**
   * @return \Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface
   */
  public function getV2V();

}
