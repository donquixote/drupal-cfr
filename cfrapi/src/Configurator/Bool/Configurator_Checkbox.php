<?php

namespace Drupal\cfrapi\Configurator\Bool;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_Checkbox implements BooleanConfiguratorInterface {

  /**
   * Builds the argument value to use at the position represented by this
   * handler.
   *
   * @param mixed $conf
   *   Setting value from configuration.
   *
   * @return bool
   */
  function confGetValue($conf) {
    return !empty($conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
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
  function confGetForm($conf, $label) {
    $element = array(
      '#title' => $label,
      '#type' => 'checkbox',
      '#default_value' => !empty($conf),
    );
    return $element;
  }

}
