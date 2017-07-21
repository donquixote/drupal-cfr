<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_ValueToValueInterface extends CfSchema_TransformableInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

  /**
   * @return \Donquixote\Cf\V2V\Value\V2V_ValueInterface
   */
  public function getV2V();

}
