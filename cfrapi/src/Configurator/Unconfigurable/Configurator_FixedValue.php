<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;

class Configurator_FixedValue extends Configurator_OptionlessBase implements ConfToPhpInterface {

  /**
   * @var mixed
   */
  private $fixedValue;

  /**
   * @var string|false|null
   */
  private $php;

  /**
   * @param mixed $fixedValue
   * @param string|false|null $php
   */
  public function __construct($fixedValue, $php = NULL) {
    $this->fixedValue = $fixedValue;
    $this->php = $php;
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

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper) {

    if (FALSE === $this->php) {
      $type = gettype($this->fixedValue);
      return $helper->notSupported($this, $conf, "This fixed value of type '$type' does not support code generation.");
    }
    elseif (NULL === $this->php) {
      return $helper->export($this->fixedValue);
    }
    else {
      return $this->php;
    }
  }
}
