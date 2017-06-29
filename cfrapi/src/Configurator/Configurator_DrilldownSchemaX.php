<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\ElementProcessor\ElementProcessor_ReparentChildren;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrapi\Util\FormUtil;

class Configurator_DrilldownSchemaX implements ConfiguratorInterface {

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
   * @var \Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface
   */
  private $drilldownSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @var callable|null
   */
  private $formProcessCallback;

  /**
   * @param \Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface $drilldownSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   */
  public function __construct(DrilldownSchemaInterface $drilldownSchema, CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator) {
    $this->drilldownSchema = $drilldownSchema;
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;
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
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    $_this = $this;

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
      '#process' => [function (array $element, array &$form_state, array &$form) use ($_this, $id, $optionsConf) {
        $element = $_this->processElement($element, $form_state, $id, $optionsConf);
        $element = FormUtil::elementsBuildDependency($element, $form_state, $form);
        return $element;
      }],
      '#after_build' => [function (array $element, array &$form_state) use ($_this) {
        return $_this->elementAfterBuild($element, $form_state);
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
   *
   * @return array
   */
  private function elementAfterBuild(array $element, array &$form_state) {
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
      '#options' => $this->drilldownSchema->getSelectOptions(),
      '#default_value' => $id,
      '#attributes' => ['class' => ['cfr-drilldown-select']],
    ];

    if (NULL !== $id && !self::idExistsInSelectOptions($id, $element['#options'])) {
      $element['#options'][$id] = t("Unknown id '@id'", ['@id' => $id]);
      $element['#element_validate'][] = function(array $element) use ($id) {
        if ((string)$id === (string)$element['#value']) {
          form_error($element, t("Unknown id %id. Maybe the id did exist in the past, but it currently does not.", ['%id' => $id]));
        }
      };
    }

    if (!$this->drilldownSchema->isOptional()) {
      $element['#required'] = TRUE;
    }
    else {
      $element['#empty_value'] = '';
    }

    return $element;
  }

  /**
   * @param string $id
   * @param array $options
   *
   * @return bool
   */
  private static function idExistsInSelectOptions($id, $options) {

    if (isset($options[$id]) && !is_array($options[$id])) {
      return TRUE;
    }

    foreach ($options as $optgroup) {
      if (is_array($optgroup) && isset($optgroup[$id])) {
        return TRUE;
      }
    }

    return FALSE;
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
   * @param mixed $optionsConf
   *
   * @return array|null
   */
  private function idConfGetOptionsForm($id, $optionsConf) {

    if (NULL === $configurator = $this->idGetConfigurator($id)) {
      return NULL;
    }

    return $configurator->confGetForm($optionsConf, $this->idGetOptionsFormLabel($id));
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  private function idGetOptionsFormLabel(
    /** @noinspection PhpUnusedParameterInspection */ $id) {
    return NULL;
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

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    return $this->idConfGetSummary($id, $optionsConf, $summaryBuilder);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return string|null
   */
  protected function idConfGetSummary($id, $optionsConf, SummaryBuilderInterface $summaryBuilder) {

    $idLabel = $this->drilldownSchema->idGetLabel($id);

    if (NULL === $id or NULL === $configurator = $this->idGetConfigurator($id)) {
      return $idLabel;
    }

    return $summaryBuilder->idConf($idLabel, $configurator, $optionsConf);
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
      if (!$this->drilldownSchema->isOptional()) {
        throw new InvalidConfigurationException("Required id missing.");
      }
      else {
        return $this->defaultValue;
      }
    }

    $value = $this->idConfGetValue($id, $optionsConf);

    return $this->drilldownSchema->idValueGetValue($id, $value);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function idConfGetValue($id, $optionsConf) {

    if (!$configurator = $this->idGetConfigurator($id)) {
      throw new InvalidConfigurationException("Unknown id '$id'.");
    }

    return $configurator->confGetValue($optionsConf);
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
    // TODO: Implement confGetPhp() method.
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
   * @param string $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  private function idGetConfigurator($id) {

    if (NULL === $cfrSchema = $this->drilldownSchema->idGetCfrSchema()) {
      return NULL;
    }

    if (FALSE === $configurator = $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator($cfrSchema, $this->cfrSchemaToConfigurator)) {
      // @todo Throw an exception instead?
      return NULL;
    }

    return $configurator;
  }
}