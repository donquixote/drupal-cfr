<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\Configurator\Optional\OptionalConfigurator_FromOptionable;
use Drupal\cfrapi\EnumMap\EnumMapInterface;
use Drupal\cfrapi\EnumMap\EnumMap;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_LegendSelect implements IdConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\EnumMap\EnumMapInterface
   */
  private $legend;

  /**
   * @var null|string
   */
  private $defaultId;

  /**
   * @param array $options
   * @param string|null $defaultId
   *
   * @return \Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect
   */
  static function createFromOptions(array $options, $defaultId = NULL) {
    $legend = new EnumMap($options);
    return new self($legend, $defaultId);
  }

  /**
   * @param \Drupal\cfrapi\EnumMap\EnumMapInterface $enumMap
   * @param string|null $defaultId
   *
   * @return \Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect
   */
  static function createRequired(EnumMapInterface $enumMap, $defaultId = NULL) {
    return new self($enumMap, $defaultId);
  }

  /**
   * @param \Drupal\cfrapi\EnumMap\EnumMapInterface $enumMap
   * @param string|null $defaultId
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfigurator_FromOptionable
   */
  static function createOptional(EnumMapInterface $enumMap, $defaultId = NULL) {
    $configurator = new self($enumMap, $defaultId);
    return new OptionalConfigurator_FromOptionable($configurator);
  }

  /**
   * @param \Drupal\cfrapi\EnumMap\EnumMapInterface $legend
   * @param string|null $defaultId
   */
  protected function __construct(EnumMapInterface $legend, $defaultId = NULL) {
    $this->legend = $legend;
    if (NULL !== $defaultId && !$legend->idIsKnown($defaultId)) {
      throw new \InvalidArgumentException('The provided default id is not among the allowed ids.');
    }
    $this->defaultId = $defaultId;
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    return $this->confBuildForm($conf, $label, TRUE);
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
    return $this->confBuildForm($conf, $label, FALSE);
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   * @param bool $required
   *
   * @return array
   */
  private function confBuildForm($conf, $label, $required) {
    if (is_numeric($conf)) {
      $conf = (string)$conf;
    }
    if (NULL === $conf || !is_string($conf)) {
      $conf = $this->defaultId;
    }
    if (!$this->legend->idIsKnown($conf)) {
      $conf = NULL;
    }
    $form = array(
      '#title' => $label,
      '#type' => 'select',
      '#options' => $this->legend->getSelectOptions(),
      '#default_value' => $conf,
    );
    if ($required) {
      $form['#required'] = TRUE;
    }
    else {
      $form['#empty_value'] = '';
    }
    return $form;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->legend->idGetLabel($conf);
  }

  /**
   * @return string
   */
  function getEmptySummary() {
    return '- ' . t('None') . ' -';
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    if (is_numeric($conf)) {
      $conf = (string)$conf;
    }
    elseif (NULL === $conf || '' === $conf) {
      return new BrokenValue($this, get_defined_vars(), 'Required id.');
    }
    elseif (!is_string($conf)) {
      return new BrokenValue($this, get_defined_vars(), 'Invalid id.');
    }
    if (!$this->legend->idIsKnown($conf)) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }
    return $conf;
  }

  /**
   * @return mixed
   */
  function getEmptyValue() {
    return NULL;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    return new ConfEmptyness_Enum();
  }
}
