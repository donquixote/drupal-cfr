<?php

namespace Drupal\cfrfamily\CfrLegendItem;

use Drupal\cfrapi\PossiblyOptionless\PossiblyOptionlessInterface;
use Drupal\cfrapi\RawConfigurator\RawConfiguratorInterface;
use Drupal\cfrapi\LegendItem\LegendItemInterface;

interface CfrLegendItemInterface extends LegendItemInterface, RawConfiguratorInterface, PossiblyOptionlessInterface {

}
