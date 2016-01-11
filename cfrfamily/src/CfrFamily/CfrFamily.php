<?php

namespace Drupal\cfrfamily\CfrFamily;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Composite\Configurator_CompositeOptional;
use Drupal\cfrfamily\CfrLegend\CfrLegend_InlineExpanded;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_CompositeWithCfrLegend;
use Drupal\cfrfamily\ConfToForm\ConfToForm_CfrLegend;
use Drupal\cfrfamily\ConfToSummary\ConfToSummary_CfrLegend;
use Drupal\cfrfamily\ConfToValue\ConfToValue_IdConf;
use Drupal\cfrfamily\IdConfToValue\IdConfToValue_IdToCfrExpanded;
use Drupal\cfrfamily\IdConfToValue\IdConfToValue_IdToConfigurator;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class CfrFamily implements CfrFamilyInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdConfToValue\IdConfToValue_IdToConfigurator
   */
  private $idConfToValue;

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  static function createExpanded(CfrLegendInterface $legend, IdToConfiguratorInterface $idToConfigurator) {
    $legend = new CfrLegend_InlineExpanded($legend);
    $idConfToValue = new IdConfToValue_IdToCfrExpanded($idToConfigurator);
    return new self($legend, $idConfToValue);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  static function create(CfrLegendInterface $legend, IdToConfiguratorInterface $idToConfigurator) {
    return new self($legend, new IdConfToValue_IdToConfigurator($idToConfigurator));
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   */
  function __construct(CfrLegendInterface $legend, IdConfToValueInterface $idConfToValue) {
    $this->legend = $legend;
    $this->idConfToValue = $idConfToValue;
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  function getCfrLegend() {
    return $this->legend;
  }

  /**
   * @return \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  function getFamilyConfigurator() {
    $confToForm = new ConfToForm_CfrLegend($this->legend, TRUE);
    $confToSummary = new ConfToSummary_CfrLegend($this->legend, TRUE);
    return new Configurator_CompositeWithCfrLegend($confToForm, $confToSummary, $this->idConfToValue, $this->legend);
  }

  /**
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function getOptionalFamilyConfigurator($defaultValue = NULL) {
    $confToForm = new ConfToForm_CfrLegend($this->legend, FALSE);
    $confToSummary = new ConfToSummary_CfrLegend($this->legend, FALSE);
    $confToValue = (new ConfToValue_IdConf($this->idConfToValue))->cloneAsOptional($defaultValue);
    return new Configurator_CompositeOptional($confToForm, $confToSummary, $confToValue, new ConfEmptyness_Key('id'));
  }
}
