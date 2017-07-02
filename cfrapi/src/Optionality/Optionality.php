<?php

namespace Drupal\cfrapi\Optionality;

use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;

class Optionality implements OptionalityInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   */
  public function __construct(
    ConfiguratorInterface $configurator,
    ConfEmptynessInterface $emptyness
  ) {
    $this->configurator = $configurator;
    $this->emptyness = $emptyness;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function getConfigurator() {
    return $this->configurator;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return $this->emptyness;
  }
}
