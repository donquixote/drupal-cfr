<?php

namespace Drupal\cfrfamily\CfrFamily;

use Drupal\cfrfamily\CfrLegend\CfrLegend_InlineExpanded;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\CfrLegendToConfigurator\CfrLegendToConfiguratorInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValue_IdToCfrExpanded;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class CfrFamily_Expanded implements CfrFamilyInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $originalLegend;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @var \Drupal\cfrfamily\CfrLegendToConfigurator\CfrLegendToConfiguratorInterface
   */
  private $cfrLegendToConfigurator;

  /**
   * @var \Drupal\cfrfamily\IdConfToValue\IdConfToValue_IdToConfigurator
   */
  private $idConfToValue;

  /**
   * @var \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  private $configurator;

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $expandedLegend;

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\CfrLegendToConfigurator\CfrLegendToConfiguratorInterface $cfrLegendToConfigurator
   */
  function __construct(CfrLegendInterface $legend, IdToConfiguratorInterface $idToConfigurator, CfrLegendToConfiguratorInterface $cfrLegendToConfigurator) {
    $this->originalLegend = $legend;
    $this->expandedLegend = new CfrLegend_InlineExpanded($legend);
    $this->idToConfigurator = $idToConfigurator;
    $this->cfrLegendToConfigurator = $cfrLegendToConfigurator;
    $this->idConfToValue = new IdConfToValue_IdToCfrExpanded($idToConfigurator);
    $this->configurator = $this->cfrLegendToConfigurator->cfrLegendGetConfigurator($this->expandedLegend, $this->idConfToValue);
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  function getCfrLegend() {
    return $this->originalLegend;
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  function getExpandedLegend() {
    return $this->expandedLegend;
  }

  /**
   * @return \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  function getFamilyConfigurator() {
    return $this->configurator;
  }

  /**
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function getOptionalFamilyConfigurator($defaultValue = NULL) {
    return $this->cfrLegendToConfigurator->cfrLegendGetOptionalConfigurator($this->originalLegend, $this->idConfToValue, $defaultValue);
  }
}
