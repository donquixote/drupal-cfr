<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\V2V\Value\V2V_ValueInterface;

class CfSchema_ValueToValue extends CfSchema_ValueToValueBase {

  /**
   * @var \Donquixote\Cf\V2V\Value\V2V_ValueInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\Cf\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(CfSchemaInterface $decorated, V2V_ValueInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\Cf\V2V\Value\V2V_ValueInterface
   */
  public function getV2V() {
    return $this->v2v;
  }
}
