<?php

namespace Donquixote\Cf\Schema\Label;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

class CfSchema_Label extends CfSchema_DecoratorBase implements CfSchema_LabelInterface {

  /**
   * @var null|string
   */
  private $label;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   * @param string|null $label
   */
  public function __construct(CfSchemaInterface $decorated, $label) {
    parent::__construct($decorated);
    $this->label = $label;
  }

  /**
   * @return string|null
   */
  public function getLabel() {
    return $this->label;
  }
}
