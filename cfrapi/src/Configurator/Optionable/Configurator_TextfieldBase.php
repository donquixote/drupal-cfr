<?php

namespace Drupal\cfrapi\Configurator\Optionable;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class Configurator_TextfieldBase implements OptionalConfiguratorInterface {

  /**
   * @var bool
   */
  private $required;

  /**
   * @param bool $required
   */
  public function __construct($required = TRUE) {
    $this->required = $required;
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

    if (!is_string($conf)) {
      $conf = NULL;
    }

    return [
      /* @see theme_textfield() */
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $conf,
      '#required' => $this->required,
    ];
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
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    if (NULL === $conf || '' === $conf || !is_string($conf)) {
      if ($this->required) {
        return t('Missing value');
      }

      return "''";
    }

    if (strlen($conf) > 30) {
      $conf = substr($conf, 0, 27) . '[..]';
    }

    return check_plain(var_export($conf, TRUE));
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return new ConfEmptyness_Enum();
  }
}
