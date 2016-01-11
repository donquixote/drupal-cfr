<?php

namespace Drupal\cfrapi\Configurator\Bool;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;

interface BooleanConfiguratorInterface extends ConfiguratorInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return bool
   *   Value to be used in the application.
   */
  function confGetValue($conf);

}
