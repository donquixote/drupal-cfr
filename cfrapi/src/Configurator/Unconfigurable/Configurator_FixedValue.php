<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;

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
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public function confGetPhp($conf) {
    if (FALSE === $this->php) {
      $type = gettype($this->fixedValue);
      throw new PhpGenerationNotSupportedException("This fixed value of type '$type' does not support code generation.");
    }
    elseif (NULL === $this->php) {
      // @todo Check if var_export() is applicable.
      return var_export($this->fixedValue, TRUE);
    }
    else {
      return $this->php;
    }
  }
}
