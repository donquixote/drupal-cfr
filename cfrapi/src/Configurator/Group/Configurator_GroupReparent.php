<?php

namespace Drupal\cfrapi\Configurator\Group;

class Configurator_GroupReparent implements GroupConfiguratorInterface {

  use ReparentConfiguratorTrait, GroupConfiguratorTrait {
    ReparentConfiguratorTrait::confGetForm insteadof GroupConfiguratorTrait;
    ReparentConfiguratorTrait::confGetSummary insteadof GroupConfiguratorTrait;
    ReparentConfiguratorTrait::confGetValue insteadof GroupConfiguratorTrait;
    GroupConfiguratorTrait::confGetForm as protected parentConfGetForm;
    GroupConfiguratorTrait::confGetSummary as protected parentConfGetSummary;
    GroupConfiguratorTrait::confGetValue as protected parentConfGetValue;
  }

}
