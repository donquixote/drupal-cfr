<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface;

interface CfSchema_DrilldownValInterface extends V2V_DrilldownInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function getDecorated();

}
