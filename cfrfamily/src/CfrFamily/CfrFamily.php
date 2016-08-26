<?php

namespace Drupal\cfrfamily\CfrFamily;

use Drupal\cfrfamily\CfrLegend\CfrLegend_InlineExpanded;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
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
    return CfrFamilyUtil::buildFamilyConfigurator($this->legend, $this->idConfToValue);
  }

  /**
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function getOptionalFamilyConfigurator($defaultValue = NULL) {
    return CfrFamilyUtil::buildOptionalFamilyConfigurator($this->legend, $this->idConfToValue, $defaultValue);
  }
}
