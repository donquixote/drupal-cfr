<?php

namespace Drupal\cfrfamily\DrilldownSchema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;

class CfSchema_Drilldown_ValueToValue extends CfSchema_Drilldown_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $valueToValue;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValue
   */
  public function __construct(
    CfSchema_DrilldownInterface $decorated,
    CfSchema_ValueToValueInterface $valueToValue
  ) {
    parent::__construct($decorated);
    $this->valueToValue = $valueToValue;
  }

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idValueGetValue($id, $value) {
    $value = parent::idValueGetValue($id, $value);
    $value = $this->valueToValue->valueGetValue($value);
    return $value;
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php) {
    $php = parent::idPhpGetPhp($id, $php);
    $php = $this->valueToValue->phpGetPhp($php);
    return $php;
  }
}
