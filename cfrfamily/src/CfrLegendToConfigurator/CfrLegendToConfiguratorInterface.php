<?php

namespace Drupal\cfrfamily\CfrLegendToConfigurator;

use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;

interface CfrLegendToConfiguratorInterface {

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   *
   * @return \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  function cfrLegendGetConfigurator(CfrLegendInterface $legend, IdConfToValueInterface $idConfToValue);

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function cfrLegendGetOptionalConfigurator(CfrLegendInterface $legend,  IdConfToValueInterface $idConfToValue, $defaultValue = NULL);

}
