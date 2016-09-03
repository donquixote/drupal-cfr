<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrapi\Util\FormUtil;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;
use Drupal\cfrfamily\IdValueToValue\IdValueToValue_Value;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

class Configurator_IdConf implements ConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Legend\LegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @var \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|null
   */
  private $idValueToValue;

  /**
   * @var bool
   */
  private $required = TRUE;

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
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface $configuratorMap
   *
   * @return \Drupal\cfrfamily\Configurator\Composite\Configurator_IdConf
   */
  static function createDefault(LegendInterface $legend, ConfiguratorMapInterface $configuratorMap) {
    $idValueToValue = new IdValueToValue_Value();
    return new self($legend, $configuratorMap, $idValueToValue);
  }

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|NULL $idValueToValue
   */
  function __construct(LegendInterface $legend, IdToConfiguratorInterface $idToConfigurator, IdValueToValueInterface $idValueToValue = NULL) {
    $this->legend = $legend;
    $this->idToConfigurator = $idToConfigurator;
    $this->idValueToValue = $idValueToValue;
  }

  /**
   * @param string $idKey
   * @param string $optionsKey
   *
   * @return static
   */
  function withKeys($idKey, $optionsKey) {
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
  function withIdLabel($idLabel) {
    $clone = clone $this;
    $clone->idLabel = $idLabel;
    return $clone;
  }

  /**
   * @param mixed $defaultValue
   *
   * @return static
   */
  function withDefaultValue($defaultValue = NULL) {
    $clone = clone $this;
    $clone->required = FALSE;
    $clone->defaultValue = $defaultValue;
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
  function confGetForm($conf, $label) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    $form = [
      '#tree' => TRUE,
    ];

    if (!$this->legend->idIsKnown($id)) {
      $id = NULL;
    }

    $form[$this->idKey] = [
      '#title' => isset($label) ? $label : $this->idLabel,
      '#type' => 'select',
      '#options' => $this->legend->getSelectOptions(),
      '#default_value' => $id,
    ];

    if ($this->required) {
      $form[$this->idKey]['#required'] = TRUE;
    }
    else {
      $form[$this->idKey]['#empty_value'] = '';
    }

    $optionsForm = [];
    if (NULL !== $id) {
      $configurator = $this->idToConfigurator->idGetConfigurator($id);
      if ($configurator && !$configurator instanceof BrokenConfiguratorInterface) {
        $optionsForm = $configurator->confGetForm($optionsConf, NULL);

        if (element_children($optionsForm)) {

          $optionsForm['#title'] = $this->idGetOptionsLabel($id);
          $optionsForm['#attributes']['class'][] = 'cfrapi-child-options';
          $optionsForm['#type'] = 'fieldset';

          // @todo Unfortunately, #collapsible fieldsets do not play nice with Views UI.
          // See https://www.drupal.org/node/2624020
          # $options_form['#collapsed'] = TRUE;
          # $options_form['#collapsible'] = TRUE;
        }
      }
    }
    $form[$this->optionsKey] = $optionsForm;

    FormUtil::onProcessBuildDependency($form);

    return $form;
  }

  /**
   * @param string $id
   *
   * @return string
   */
  private function idGetOptionsLabel($id) {
    $idLabel = $this->legend->idGetLabel($id);
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
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    $idLabel = $this->legend->idGetLabel($id);

    if (NULL === $id or NULL === $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return $idLabel;
    }

    return $summaryBuilder->idConf($idLabel, $configurator, $optionsConf);
  }

  /**
   * @return string
   */
  function getEmptySummary() {
    return t('None');
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    if (NULL === $id) {
      if (!$this instanceof OptionalConfiguratorInterface) {
        return new BrokenValue($this, get_defined_vars(), 'Required.');
      }
      else {
        return $this->defaultValue;
      }
    }

    if (!$configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }

    $value = $configurator->confGetValue($optionsConf);

    if ($value instanceof BrokenValueInterface) {
      return $value;
    }

    if (NULL !== $this->idValueToValue) {
      return $this->idValueToValue->idValueGetValue($id, $value);
    }

    return [
      $this->idKey => $id,
      $this->optionsKey => $value,
    ];
  }

  /**
   * @return mixed
   */
  function getEmptyValue() {
    return $this->defaultValue;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    return new ConfEmptyness_Key($this->idKey);
  }
}
