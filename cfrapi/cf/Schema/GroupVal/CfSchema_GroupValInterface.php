<?php

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_GroupValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  public function getDecorated();

  /**
   * @return \Donquixote\Cf\V2V\Group\V2V_GroupInterface
   */
  public function getV2V();

}
