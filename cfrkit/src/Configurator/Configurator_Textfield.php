<?php

namespace Drupal\cfrkit\Configurator;

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
}
