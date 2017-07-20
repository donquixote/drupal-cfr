<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;
use Drupal\cfrapi\RawConfigurator\RawConfiguratorInterface;

interface ConfiguratorInterface extends RawConfiguratorInterface, ConfToValueInterface {

}
