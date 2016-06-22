<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpUtil;
use Drupal\cfrapi\PhpProvider\PhpProviderInterface;

class ValueProvider_FromCfrConf implements ValueProviderInterface, PhpProviderInterface {

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
   */
  public function getValue() {
    return $this->configurator->confGetValue($this->conf);
  }

  /**
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public function getPhp() {
    return ConfToPhpUtil::objConfGetPhp($this->configurator, $this->conf);
  }
}
