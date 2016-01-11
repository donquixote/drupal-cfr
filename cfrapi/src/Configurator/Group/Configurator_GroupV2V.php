<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfToValue\V2VConfToValueTrait;

/**
 * Group configurator with an additional ValueToValue object.
 */
class Configurator_GroupV2V implements ConfiguratorInterface  {

  use V2VConfToValueTrait, GroupConfiguratorTrait {
    V2VConfToValueTrait::confGetValue insteadof GroupConfiguratorTrait;
    GroupConfiguratorTrait::confGetValue as confGetRawValue;
  }

}
