<?php

namespace Drupal\cfrapi\Configurator\Optionable;

use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;

class OptionableConfigurator_Fixed implements OptionableConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  private $optionalConfigurator;

  /**
   * @param \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface $optionalConfigurator
   */
  public function __construct(OptionalConfiguratorInterface $optionalConfigurator) {
    $this->optionalConfigurator = $optionalConfigurator;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|null
   */
  public function getOptionalConfigurator() {
    return $this->optionalConfigurator;
  }
}
