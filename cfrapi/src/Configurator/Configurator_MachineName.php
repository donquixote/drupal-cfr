<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_MachineName implements OptionalConfiguratorInterface {

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
      /* @see form_process_machine_name() */
      '#type' => 'machine_name',
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

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {

    if (!is_string($conf)) {
      return '';
    }

    if (NULL !== $msg = $this->machineNameGetError($conf)) {
      throw new InvalidConfigurationException($msg);
    }

    return $conf;
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

    if (!is_string($conf)) {
      return "''";
    }

    if (NULL !== $msg = $this->machineNameGetError($conf)) {
      return $helper->incompatibleConfiguration($conf,$msg);
    }

    return var_export($conf, TRUE);
  }

  /**
   * @param mixed $value
   *
   * @return null|string
   * @see form_process_machine_name()
   */
  private function machineNameGetError($value) {

    // Verify that the machine name not only consists of replacement tokens.
    if (preg_match('@^_+$@', $value)) {
      return 'The machine-readable name must contain unique characters.';
    }

    // Verify that the machine name contains no disallowed characters.
    if (preg_match('@[^a-z0-9_]+@', $value)) {
      // Since a hyphen is the most common alternative replacement character,
      // a corresponding validation error message is supported here.
      return 'The machine-readable name must contain only lowercase letters, numbers, and underscores.';
    }

    return NULL;
  }
}
