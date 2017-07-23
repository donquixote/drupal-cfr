<?php

namespace Drupal\cfrapi\Configurator\Optionable;

interface OptionableConfiguratorInterface {

  /**
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|null
   */
  public function getOptionalConfigurator();

}
