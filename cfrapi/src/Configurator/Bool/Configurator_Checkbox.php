<?php

namespace Drupal\cfrapi\Configurator\Bool;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_Checkbox implements ConfiguratorInterface, ConfToPhpInterface {

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
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetPhp($conf) {
    return !empty($conf) ? 'true' : 'false';
  }
}
