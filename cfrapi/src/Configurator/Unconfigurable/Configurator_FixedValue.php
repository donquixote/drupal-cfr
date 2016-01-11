<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

class Configurator_FixedValue extends UnconfigurableConfiguratorBase {

  /**
   * @var mixed
   */
  private $fixedValue;

  /**
   * @param mixed $fixedValue
   */
  function __construct($fixedValue) {
    $this->fixedValue = $fixedValue;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    return $this->fixedValue;
  }
}
