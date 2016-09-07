<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

class Configurator_FixedValue extends Configurator_OptionlessBase {

  /**
   * @var mixed
   */
  private $fixedValue;

  /**
   * @param mixed $fixedValue
   */
  public function __construct($fixedValue) {
    $this->fixedValue = $fixedValue;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $this->fixedValue;
  }
}
