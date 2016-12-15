<?php

namespace Drupal\cfrkit\Configurator;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Configurator\Optionable\Configurator_TextfieldBase;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;

class Configurator_Textfield extends Configurator_TextfieldBase implements ConfToPhpInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return is_string($conf) ? $conf : '';
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
    return is_string($conf) ? var_export($conf, TRUE) : '';
  }
}
