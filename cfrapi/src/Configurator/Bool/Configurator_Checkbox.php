<?php

namespace Drupal\cfrapi\Configurator\Bool;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_Checkbox implements ConfiguratorInterface {

  /**
   * Builds the argument value to use at the position represented by this
   * handler.
   *
   * @param mixed $conf
   *   Setting value from configuration.
   *
   * @return bool
   */
  public function confGetValue($conf) {
    return !empty($conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return !empty($conf)
      ? t('Yes')
      : t('No');
  }

  /**
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   *   A form element(s) array.
   */
  public function confGetForm($conf, $label) {
    $element = [
      '#title' => $label,
      '#type' => 'checkbox',
      '#default_value' => !empty($conf),
    ];
    return $element;
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
    return !empty($conf) ? 'true' : 'false';
  }
}
