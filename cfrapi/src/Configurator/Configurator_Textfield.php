<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\Optionable\Configurator_TextfieldBase;

class Configurator_Textfield extends Configurator_TextfieldBase {

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
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    return is_string($conf) ? var_export($conf, TRUE) : '';
  }
}
