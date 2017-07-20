<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_ValueToValueBase extends CfSchema_DecoratorBase implements CfSchema_ValueToValueInterface {

  /**
   * @param string|null $label
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function withLabel($label) {
    return new CfSchema_Label($this, $label);
  }

}
