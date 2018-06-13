<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

/**
 * @see \Drupal\cfrapi\ValueProvider\ValueProvider_FromCfrConf
 */
class Configurator_FromValueProvider extends Configurator_OptionlessBase {

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
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    return $this->valueProvider->getValue();
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    return $this->valueProvider->getPhp($helper);
  }
}
