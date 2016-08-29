<?php

namespace Drupal\cfrapi\Configurator\Optionable;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/** @noinspection PhpDeprecationInspection */
abstract class Configurator_TextfieldBase implements OptionableConfiguratorInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    if (!is_string($conf)) {
      $conf = NULL;
    }
    return array(
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $conf,
      '#required' => TRUE,
    );
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *   An object that controls the format of the summary.
   *
   * @return mixed|string|null
   *   A string summary is always allowed. But other values may be returned if
   *   $summaryBuilder generates them.
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    if (!is_string($conf)) {
      return NULL;
    }
    return check_plain($conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetOptionalForm($conf, $label) {
    $form = $this->confGetForm($conf, $label);
    unset($form['#required']);
    return $form;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    return new ConfEmptyness_Enum();
  }

  /**
   * @return string
   */
  function getEmptySummary() {
    return '- ' . t('None') . ' -';
  }

  /**
   * @return mixed
   */
  function getEmptyValue() {
    return NULL;
  }
}
