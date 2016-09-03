<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

/**
 * @see \Drupal\cfrapi\ValueProvider\ValueProvider_FromCfrConf
 */
class Configurator_FromValueProvider extends UnconfigurableConfiguratorBase {

  /**
   * @var \Drupal\cfrapi\ValueProvider\ValueProviderInterface
   */
  private $valueProvider;

  /**
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $valueProvider
   */
  public function __construct(ValueProviderInterface $valueProvider) {
    $this->valueProvider = $valueProvider;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $this->valueProvider->getValue();
  }
}
