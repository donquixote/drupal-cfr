<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\ElementProcessor\ElementProcessor_ReparentChildren;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
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
   * @var callable|null
   */
  private $formProcessCallback;

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
   * @param callable $formProcessCallback
   *
   * @return static
   */
  public function withFormProcessCallback($formProcessCallback) {
    $clone = clone $this;
    $clone->formProcessCallback = $formProcessCallback;
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

    $obj = $this;

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['cfr-drilldown']],
      '#tree' => TRUE,
      $this->idKey => $this->idBuildSelectElement($id, $label),
      '#input' => TRUE,
      '#title' => $label,
      '#default_value' => $conf = [
        $this->idKey => $id,
        $this->optionsKey => $optionsConf,
      ],
      '#process' => [function (array $element, array &$form_state, array &$form) use ($obj, $id, $optionsConf) {
        $element = $obj->processElement($element, $form_state, $id, $optionsConf);
        $element = FormUtil::elementsBuildDependency($element, $form_state, $form);
        return $element;
      }],
      '#after_build' => [function (array $element, array &$form_state) use ($obj, $id, $optionsConf) {
        return $obj->elementAfterBuild($element, $form_state, $id, $optionsConf);
      }],
    ];

    if (NULL !== $this->formProcessCallback) {
      $form = call_user_func($this->formProcessCallback, $form);
    }

    return $form;
  }

  /**
   * @param array $element
   * @param array $form_state
   * @param string $defaultId
   * @param mixed $defaultOptionsConf
   *
   * @return array
   */
  private function processElement(array $element, array &$form_state, $defaultId, $defaultOptionsConf) {
    $value = $element['#value'];
    $id = isset($value[$this->idKey]) ? $value[$this->idKey] : NULL;
    if ($id !== $defaultId) {
      $defaultOptionsConf = NULL;
    }
    $prevId = isset($value['_previous_id']) ? $value['_previous_id'] : NULL;
    if (NULL !== $prevId && $id !== $prevId && isset($form_state['input'])) {
      // Don't let values leak from one plugin to the other.
      ConfUtil::confUnsetNestedValue($form_state['input'], array_merge($element['#parents'], [$this->optionsKey]));
      # $defaultOptionsConf = NULL;
    }
    $element[$this->optionsKey] = $this->idConfBuildOptionsFormWrapper($id, $defaultOptionsConf);
    $element[$this->optionsKey]['_previous_id'] = [
      '#type' => 'hidden',
      '#value' => $id,
      '#parents' => array_merge($element['#parents'], ['_previous_id']),
      '#weight' => -99,
    ];
    return $element;
  }

  /**
   * @param array $element
   * @param array $form_state
   * @param string $id
   * @param mixed $defaultOptionsConf
   *
   * @return array
   */
  private function elementAfterBuild(array $element, array &$form_state, $id, $defaultOptionsConf) {
    ConfUtil::confUnsetNestedValue($form_state['input'], array_merge($element['#parents'], ['_previous_id']));
    ConfUtil::confUnsetNestedValue($form_state['values'], array_merge($element['#parents'], ['_previous_id']));
    return $element;
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
      '#attributes' => ['class' => ['cfr-drilldown-select']],
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
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    if (NULL === $id) {
      if ($this->required) {
        throw new InvalidConfigurationException("Required id missing.");
      }
      else {
        return $this->defaultValue;
      }
    }

    $value = $this->idConfGetValue($id, $optionsConf);

    if (NULL === $this->idValueToValue) {
      return $value;
    }

    return $this->idValueToValue->idValueGetValue($id, $value);
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

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    if (NULL === $id) {
      if ($this->required) {
        return $helper->incompatibleConfiguration($conf, "Required id missing.");
      }
      else {
        return $helper->export($this->defaultValue);
      }
    }

    $php = $this->idConfGetPhp($id, $optionsConf, $helper);

    if (NULL === $this->idValueToValue) {
      return $php;
    }

    return $this->idValueToValue->idPhpGetPhp($id, $php, $helper);
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
   * @param string $id
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function idPhpGetPhp($id, $php, CfrCodegenHelperInterface $helper) {

    return '['
    . "\n  " . var_export($this->idKey, TRUE) . ' => ' . var_export($id, TRUE) . ','
    . "\n  " . var_export($this->optionsKey, TRUE) . ' => ' . $php . ','
    . "\n]";
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

  /**
   * @param string $id
   * @param mixed $optionsConf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  abstract protected function idConfGetPhp($id, $optionsConf, CfrCodegenHelperInterface $helper);
}
