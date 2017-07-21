<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_DrilldownValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function getDecorated();

  /**
   * @return \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V();

}
