<?php

namespace Drupal\cfrapi\Configurator;

class Configurator_Passthru extends Configurator_DecoratorBase {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $conf;
  }

}
