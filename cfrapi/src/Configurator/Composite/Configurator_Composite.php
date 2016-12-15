<?php

namespace Drupal\cfrapi\Configurator\Composite;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpUtil;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_Composite implements ConfiguratorInterface, ConfToPhpInterface {

  /**
   * @var \Drupal\cfrapi\ConfToForm\ConfToFormInterface
   */
  private $confToForm;

  /**
   * @var \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface
   */
  private $confToSummary;

  /**
   * @var \Drupal\cfrapi\ConfToValue\ConfToValueInterface
   */
  private $confToValue;

  /**
   * @param \Drupal\cfrapi\ConfToForm\ConfToFormInterface $confToForm
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param \Drupal\cfrapi\ConfToValue\ConfToValueInterface $confToValue
   */
  public function __construct(ConfToFormInterface $confToForm, ConfToSummaryInterface $confToSummary, ConfToValueInterface $confToValue) {
    $this->confToForm = $confToForm;
    $this->confToSummary = $confToSummary;
    $this->confToValue = $confToValue;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return $this->confToForm->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->confToSummary->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $this->confToValue->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper) {
    return ConfToPhpUtil::objConfGetPhp($this->confToValue, $conf, $helper);
  }
}
