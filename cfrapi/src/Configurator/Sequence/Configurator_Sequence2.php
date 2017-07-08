<?php

namespace Drupal\cfrapi\Configurator\Sequence;

use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;

class Configurator_Sequence2 extends Configurator_SequenceBase {

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   */
  public function __construct(ConfiguratorInterface $configurator, ConfEmptynessInterface $emptyness) {
    parent::__construct($configurator, $emptyness);
  }
}
