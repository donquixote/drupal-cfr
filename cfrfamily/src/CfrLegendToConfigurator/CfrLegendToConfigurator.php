<?php

namespace Drupal\cfrfamily\CfrLegendToConfigurator;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Composite\Configurator_CompositeOptional;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_CompositeWithCfrLegend;
use Drupal\cfrfamily\ConfToForm\ConfToForm_CfrLegend;
use Drupal\cfrfamily\ConfToSummary\ConfToSummary_CfrLegend;
use Drupal\cfrfamily\ConfToValue\ConfToValue_IdConf;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;

class CfrLegendToConfigurator implements CfrLegendToConfiguratorInterface {

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   *
   * @return \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  function cfrLegendGetConfigurator(CfrLegendInterface $legend, IdConfToValueInterface $idConfToValue) {
    $confToForm = new ConfToForm_CfrLegend($legend, TRUE);
    $confToSummary = new ConfToSummary_CfrLegend($legend, TRUE);
    return new Configurator_CompositeWithCfrLegend($confToForm, $confToSummary, $idConfToValue, $legend);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function cfrLegendGetOptionalConfigurator(CfrLegendInterface $legend, IdConfToValueInterface $idConfToValue, $defaultValue = NULL) {
    $confToForm = new ConfToForm_CfrLegend($legend, FALSE);
    $confToSummary = new ConfToSummary_CfrLegend($legend, FALSE);
    $confToValue = (new ConfToValue_IdConf($idConfToValue))->cloneAsOptional($defaultValue);
    return new Configurator_CompositeOptional($confToForm, $confToSummary, $confToValue, new ConfEmptyness_Key('id'));
  }
}
