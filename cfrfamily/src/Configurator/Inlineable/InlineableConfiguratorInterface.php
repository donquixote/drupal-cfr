<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;

interface InlineableConfiguratorInterface extends ConfiguratorInterface, CfrLegendProviderInterface {

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  function idConfGetValue($id, $optionsConf);

}
