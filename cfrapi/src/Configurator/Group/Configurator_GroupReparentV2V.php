<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfToValue\V2VConfToValueTrait;

class Configurator_GroupReparentV2V implements ConfiguratorInterface {

  use V2VConfToValueTrait, ReparentConfiguratorTrait, GroupConfiguratorTrait {
    V2VConfToValueTrait::confGetValue insteadof ReparentConfiguratorTrait, GroupConfiguratorTrait;
    ReparentConfiguratorTrait::confGetForm insteadof V2VConfToValueTrait, GroupConfiguratorTrait;
    ReparentConfiguratorTrait::confGetSummary insteadof V2VConfToValueTrait, GroupConfiguratorTrait;
    ReparentConfiguratorTrait::confGetValue as protected confGetRawValue;
    GroupConfiguratorTrait::confGetForm as protected parentConfGetForm;
    GroupConfiguratorTrait::confGetSummary as protected parentConfGetSummary;
    GroupConfiguratorTrait::confGetValue as protected parentConfGetValue;
  }

}
