<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;
use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrapi\Util\FormUtil;

/** @noinspection PhpDeprecationInspection
 * @deprecated
 */
class Configurator_IdConfAdvanced implements OptionableConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Legend\LegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface
   */
  private $configuratorMap;

  /**
   * @var \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|null
   */
  private $idValueToValue;

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
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface $idValueToValue
   * @param mixed|null $defaultValue
   */
  function __construct(LegendInterface $legend, ConfiguratorMapInterface $configuratorMap, IdValueToValueInterface $idValueToValue = NULL, $defaultValue = NULL) {
    $this->legend = $legend;
    $this->configuratorMap = $configuratorMap;
    $this->idValueToValue = $idValueToValue;
    $this->defaultValue = $defaultValue;
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
  function confBuildForm($conf, $label, $required) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    $form = array(
      '#tree' => TRUE,
      '#input' => TRUE,
      '#type' => NULL,
      '#value_callback' => '_x',
      '#process' => array('_y'),
    );

    if (!$this->legend->idIsKnown($id)) {
      $id = NULL;
    }

    $form[$this->idKey] = array(
      '#title' => isset($label) ? $label : $this->idLabel,
      '#type' => 'select',
      '#options' => $this->legend->getSelectOptions(),
      '#default_value' => $id,
    );

    if ($required) {
      $form[$this->idKey]['#required'] = TRUE;
    }
    else {
      $form[$this->idKey]['#empty_value'] = '';
    }

    $optionsForm = array();
    if (NULL !== $id) {
      $configurator = $this->configuratorMap->idGetConfigurator($id);
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
      : t('Options for "@name"', array('@name' => $idLabel));
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return mixed
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    $idLabel = $this->legend->idGetLabel($id);

    if (NULL === $id || NULL === $configurator = $this->configuratorMap->idGetConfigurator($id)) {
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

    if (!$configurator = $this->configuratorMap->idGetConfigurator($id)) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }

    $value = $configurator->confGetValue($optionsConf);

    if (NULL !== $this->idValueToValue && !$value instanceof BrokenValueInterface) {
      $value = $this->idValueToValue->idValueGetValue($id, $value);
    }

    return $value;
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
