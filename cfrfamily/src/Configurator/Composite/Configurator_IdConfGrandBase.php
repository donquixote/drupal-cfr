<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\ElementProcessor\ElementProcessor_ReparentChildren;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\FormUtil;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

abstract class Configurator_IdConfGrandBase implements OptionalConfiguratorInterface, IdValueToValueInterface {

  /**
   * @var bool
   */
  private $required;

  /**
   * @var mixed|null
   */
  private $defaultValue;

  /**
   * @var string|null
   */
  private $idLabel;

  /**
   * @var string
   */
  private $idKey = 'id';

  /**
   * @var string
   */
  private $optionsKey = 'options';

  /**
   * @var \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|null
   */
  private $idValueToValue;

  /**
   * @param bool $required
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|null $idValueToValue
   * @param string $idKey
   * @param string $optionsKey
   */
  public function __construct($required, IdValueToValueInterface $idValueToValue = NULL, $idKey = 'id', $optionsKey = 'options') {
    $this->required = $required;
    $this->idValueToValue = $idValueToValue;
    $this->idKey = $idKey;
    $this->optionsKey = $optionsKey;
  }

  /**
   * @param string $idKey
   * @param string $optionsKey
   *
   * @return static
   */
  public function withKeys($idKey, $optionsKey) {
    $clone = clone $this;
    $clone->idKey = $idKey;
    $clone->optionsKey = $optionsKey;
    return $clone;
  }

  /**
   * @param string $idLabel
   *
   * @return static
   */
  public function withIdLabel($idLabel) {
    $clone = clone $this;
    $clone->idLabel = $idLabel;
    return $clone;
  }

  /**
   * @param mixed $defaultValue
   *
   * @return static
   */
  public function withDefaultValue($defaultValue = NULL) {
    $clone = clone $this;
    $clone->required = FALSE;
    $clone->defaultValue = $defaultValue;
    return $clone;
  }

  /**
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface $idValueToValue
   *
   * @return static
   */
  public function withIdValueToValue(IdValueToValueInterface $idValueToValue) {
    $clone = clone $this;
    $clone->idValueToValue = $idValueToValue;
    return $clone;
  }

  /**
   * @return static
   */
  public function withIdValueRepackaging() {
    return $this->withIdValueToValue($this);
  }

  /**
   * @return static
   */
  public function withoutIdValueToValue() {
    $clone = clone $this;
    $clone->idValueToValue = NULL;
    return $clone;
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['cfr-drilldown']],
      '#tree' => TRUE,
      $this->idKey => $this->idBuildSelectElement($id, $label),
      $this->optionsKey => $this->idConfBuildOptionsFormWrapper($id, $optionsConf),
      '#process' => [[FormUtil::class, 'elementsBuildDependency']],
    ];

    return $form;
  }

  /**
   * @param string $id
   * @param string|null $label
   *
   * @return array
   */
  private function idBuildSelectElement($id, $label) {

    $element = [
      '#title' => ($label !== NULL) ? $label : $this->idLabel,
      '#type' => 'select',
      '#options' => $this->getSelectOptions(),
      '#default_value' => $id,
    ];

    if ($this->required) {
      $element['#required'] = TRUE;
    }
    else {
      $element['#empty_value'] = '';
    }

    return $element;
  }

  /**
   * @param string|null $id
   * @param mixed $optionsConf
   *
   * @return array
   */
  private function idConfBuildOptionsFormWrapper($id, $optionsConf) {

    if (NULL === $id) {
      return [];
    }

    $optionsForm = $this->idConfGetOptionsForm($id, $optionsConf);
    if (empty($optionsForm)) {
      return [];
    }

    // @todo Unfortunately, #collapsible fieldsets do not play nice with Views UI.
    // See https://www.drupal.org/node/2624020
    # $options_form['#collapsed'] = TRUE;
    # $options_form['#collapsible'] = TRUE;
    return [
      '#type' => 'container',
      # '#type' => 'fieldset',
      # '#title' => $this->idGetOptionsLabel($id),
      '#attributes' => ['class' => ['cfrapi-child-options']],
      '#process' => [new ElementProcessor_ReparentChildren(['fieldset_content' => []])],
      'fieldset_content' => $optionsForm,
    ];
  }

  /**
   * @param string $id
   *
   * @return string
   */
  private function idGetOptionsLabel($id) {
    $idLabel = $this->idGetLabel($id);
    return empty($idLabel)
      ? t('Options')
      : t('Options for "@name"', ['@name' => $idLabel]);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    return $this->idConfGetSummary($id, $optionsConf, $summaryBuilder);
  }

  /**
   * @return string
   */
  public function getEmptySummary() {
    return t('None');
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    if (NULL === $id) {
      if ($this->required) {
        return new BrokenValue($this, get_defined_vars(), 'Required.');
      }
      else {
        return $this->defaultValue;
      }
    }

    $value = $this->idConfGetValue($id, $optionsConf);

    if (NULL === $this->idValueToValue) {
      return $value;
    }

    if ($value instanceof BrokenValueInterface) {
      return $value;
    }

    return $this->idValueToValue->idValueGetValue($id, $value);
  }

  /**
   * @return mixed
   */
  public function getEmptyValue() {
    return $this->defaultValue;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   */
  public function getEmptyness() {
    return $this->required
      ? NULL
      : new ConfEmptyness_Key($this->idKey);
  }

  /**
   * @param string $id
   * @param mixed $value
   *
   * @return array
   */
  public function idValueGetValue($id, $value) {
    return [$this->idKey => $id, $this->optionsKey => $value];
  }

  /**
   * @param mixed $conf
   *
   * @return array
   */
  private function confGetIdOptions($conf) {

    if (!is_array($conf)) {
      return [NULL, NULL];
    }

    if (!isset($conf[$this->idKey])) {
      return [NULL, NULL];
    }

    if ('' === $id = $conf[$this->idKey]) {
      return [NULL, NULL];
    }

    if (!is_string($id) && !is_int($id)) {
      return [NULL, NULL];
    }

    if (!isset($conf[$this->optionsKey])) {
      return [$id, NULL];
    }
    else {
      return [$id, $conf[$this->optionsKey]];
    }
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  abstract protected function getSelectOptions();

  /**
   * @param string $id
   *
   * @return string
   */
  abstract protected function idGetLabel($id);

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return array|null
   */
  abstract protected function idConfGetOptionsForm($id, $optionsConf);

  /**
   * @param string $id
   * @param mixed $optionsConf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return string|null
   */
  abstract protected function idConfGetSummary($id, $optionsConf, SummaryBuilderInterface $summaryBuilder);

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  abstract protected function idConfGetValue($id, $optionsConf);
}
