<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;

class ValueProvider_FromCfrConf implements ValueProviderInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param mixed $conf
   */
  function __construct(ConfiguratorInterface $configurator, $conf) {
    $this->configurator = $configurator;
    $this->conf = $conf;
  }

  /**
   * @return mixed
   */
  function getValue() {
    return $this->configurator->confGetValue($this->conf);
  }
}
