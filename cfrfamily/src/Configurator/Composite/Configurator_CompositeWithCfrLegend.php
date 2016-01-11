<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\Configurator\Composite\Configurator_Composite;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;
use Drupal\cfrfamily\ConfToValue\ConfToValue_IdConf;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;

class Configurator_CompositeWithCfrLegend extends Configurator_Composite implements InlineableConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface
   */
  private $idConfToValue;

  /**
   * @param \Drupal\cfrapi\ConfToForm\ConfToFormInterface $confToForm
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   */
  function __construct(
    ConfToFormInterface $confToForm,
    ConfToSummaryInterface $confToSummary,
    IdConfToValueInterface $idConfToValue,
    CfrLegendInterface $legend
  ) {
    $confToValue = new ConfToValue_IdConf($idConfToValue);
    parent::__construct($confToForm, $confToSummary, $confToValue);
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
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  function idConfGetValue($id, $optionsConf) {
    return $this->idConfToValue->idConfGetValue($id, $optionsConf);
  }
}
