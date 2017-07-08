<?php

namespace Drupal\cfrapi\Configurator\Broken;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_AlwaysEmpty;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;

class OptionalBrokenConfiguratorBase extends BrokenConfiguratorBase implements OptionalConfiguratorInterface {

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   *   An emptyness object, or
   *   NULL, if the configurator is in fact required and thus no valid conf
   *   counts as empty.
   */
  public function getEmptyness() {
    return new ConfEmptyness_AlwaysEmpty();
  }
}
