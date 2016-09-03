<?php

namespace Drupal\cfrfamily\IdToConfigurator;

interface IdToConfiguratorInterface {

  /**
   * @param string|int $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function idGetConfigurator($id);

}
