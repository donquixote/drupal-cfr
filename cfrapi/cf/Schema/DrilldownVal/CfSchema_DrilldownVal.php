<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface;

class CfSchema_DrilldownVal extends CfSchema_DrilldownValBase {

  /**
   * @var \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  public function __construct(CfSchema_DrilldownInterface $decorated, V2V_DrilldownInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V() {
    return $this->v2v;
  }
}
