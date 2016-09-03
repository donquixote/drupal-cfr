<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorBase;
use Drupal\cfrfamily\ConfToForm\ConfToForm_CfrLegend;
use Drupal\cfrfamily\ConfToSummary\ConfToSummary_CfrLegend;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class Configurator_IdConfExpanded extends InlineableConfiguratorBase {

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
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   */
  public function __construct(CfrLegendInterface $legend, IdToConfiguratorInterface $idToConfigurator) {
    $this->legend = $legend;
    $this->idToConfigurator = $idToConfigurator;
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
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    $confToForm = new ConfToForm_CfrLegend($this->legend, $this->required);

    return $confToForm->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    $confToSummary = new ConfToSummary_CfrLegend($this->legend, $this->required);

    return $confToSummary->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return new ConfEmptyness_Key($this->idKey);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $optionsConf) {

    if (NULL === $id) {
      if ($this->required) {
        return new BrokenValue($this, get_defined_vars(), 'Required.');
      }
      else {
        return $this->defaultValue;
      }
    }

    if (!$configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }

    return $configurator->confGetValue($optionsConf);
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  public function getCfrLegend() {
    return $this->legend;
  }
}
