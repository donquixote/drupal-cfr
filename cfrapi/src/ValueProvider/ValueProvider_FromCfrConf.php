<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
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
  public function __construct(ConfiguratorInterface $configurator, $conf) {
    $this->configurator = $configurator;
    $this->conf = $conf;
  }

  /**
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function getValue() {
    return $this->configurator->confGetValue($this->conf);
  }

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper) {
    return $this->configurator->confGetPhp($this->conf, $helper);
  }
}
