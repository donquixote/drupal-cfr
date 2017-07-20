<?php

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\V2V\Group\V2V_GroupInterface;

interface CfSchema_GroupValInterface extends V2V_GroupInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  public function getDecorated();

}
