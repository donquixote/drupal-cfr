<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Schema\DecoratorBase\CfSchema_DecoratorBase;

abstract class CfSchema_ValueToValueBase extends CfSchema_DecoratorBase implements CfSchema_ValueToValueInterface {

  /**
   * @var string|null
   */
  private $label;

  /**
   * @param string|null $label
   *
   * @return static
   */
  public function withLabel($label) {
    $clone = clone $this;
    $clone->label = $label;
    return $clone;
  }

  /**
   * @return string|null
   */
  public function getLabel() {
    return $this->label;
  }
}
