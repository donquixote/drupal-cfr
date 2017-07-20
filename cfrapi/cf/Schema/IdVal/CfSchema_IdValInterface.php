<?php

namespace Donquixote\Cf\Schema\IdVal;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\V2V\Id\V2V_IdInterface;

interface CfSchema_IdValInterface extends V2V_IdInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  public function getDecorated();

}
