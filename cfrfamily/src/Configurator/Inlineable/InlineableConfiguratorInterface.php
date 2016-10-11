<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;

interface InlineableConfiguratorInterface extends ConfiguratorInterface, CfrLegendProviderInterface, IdConfToValueInterface {

  /**
   * @param string|null $id
   * @param mixed $optionsConf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function idConfGetValue($id, $optionsConf);
}
