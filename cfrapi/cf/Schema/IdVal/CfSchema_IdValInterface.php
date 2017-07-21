<?php

namespace Donquixote\Cf\Schema\IdVal;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_IdValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  public function getDecorated();

  /**
   * @return \Donquixote\Cf\V2V\Id\V2V_IdInterface
   */
  public function getV2V();

}
