<?php

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\V2V\Group\V2V_GroupInterface;

class CfSchema_GroupVal extends CfSchema_GroupValBase {

  /**
   * @var \Donquixote\Cf\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $decorated
   * @param \Donquixote\Cf\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(CfSchema_GroupInterface $decorated, V2V_GroupInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\Cf\V2V\Group\V2V_GroupInterface
   */
  public function getV2V() {
    return $this->v2v;
  }
}
