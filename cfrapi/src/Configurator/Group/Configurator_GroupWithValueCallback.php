<?php

namespace Drupal\cfrapi\Configurator\Group;

class Configurator_GroupWithValueCallback extends Configurator_GroupBase {

  /**
   * @var callable
   */
  private $valueCallback;

  /**
   * @param callable $valueCallback
   */
  public function __construct($valueCallback) {
    if (!is_callable($valueCallback)) {
      throw new \InvalidArgumentException("Argument must be callable.");
    }
    $this->valueCallback = $valueCallback;
  }

  /**
   * @param mixed[]|mixed $conf
   *
   * @return \Drupal\cfrapi\BrokenValue\BrokenValueInterface|mixed|\mixed[]
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    if (!is_array($value)) {
      return $value;
    }
    return call_user_func($this->valueCallback, $value);
  }

}
