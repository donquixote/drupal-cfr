<?php

namespace Drupal\cfrfamily\IdToConfigurator;

interface IdToConfiguratorInterface {

  /**
   * @param string|int $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  function idGetConfigurator($id);

}
