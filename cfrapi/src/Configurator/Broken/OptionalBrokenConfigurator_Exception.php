<?php

namespace Drupal\cfrapi\Configurator\Broken;

use Drupal\cfrapi\Exception\ConfiguratorCreationException;

class OptionalBrokenConfigurator_Exception extends OptionalBrokenConfiguratorBase {

  /**
   * @var \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  private $exception;

  /**
   * @param \Drupal\cfrapi\Exception\ConfiguratorCreationException $exception
   */
  public function __construct(ConfiguratorCreationException $exception) {
    $this->exception = $exception;
  }

}
