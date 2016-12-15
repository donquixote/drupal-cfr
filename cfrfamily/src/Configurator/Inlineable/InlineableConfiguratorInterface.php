<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;

interface InlineableConfiguratorInterface extends ConfiguratorInterface, CfrLegendProviderInterface, IdConfToValueInterface {

}
