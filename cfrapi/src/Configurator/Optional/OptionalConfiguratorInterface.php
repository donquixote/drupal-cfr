<?php

namespace Drupal\cfrapi\Configurator\Optional;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;

interface OptionalConfiguratorInterface extends ConfiguratorInterface {

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness();

}
