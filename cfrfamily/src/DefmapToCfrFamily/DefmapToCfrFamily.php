<?php

namespace Drupal\cfrfamily\DefmapToCfrFamily;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\CfrFamily\CfrFamily;
use Drupal\cfrfamily\CfrLegend\CfrLegend_FromDefmap;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\CfrLegendToConfigurator\CfrLegendToConfiguratorInterface;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMap_FromDefinitionMap;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class DefmapToCfrFamily implements DefmapToCfrFamilyInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   */
  private $definitionToConfigurator;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @var \Drupal\cfrfamily\CfrLegendToConfigurator\CfrLegendToConfiguratorInterface
   */
  private $cfrLegendToConfigurator;

  /**
   * @param \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface $definitionToConfigurator
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param \Drupal\cfrfamily\CfrLegendToConfigurator\CfrLegendToConfiguratorInterface $cfrLegendToConfigurator
   */
  function __construct(
    DefinitionToConfiguratorInterface $definitionToConfigurator,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    CfrLegendToConfiguratorInterface $cfrLegendToConfigurator
  ) {
    $this->definitionToConfigurator = $definitionToConfigurator;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
    $this->cfrLegendToConfigurator = $cfrLegendToConfigurator;
  }

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  function defmapGetCfrFamily(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL) {
    $configuratorMap = new ConfiguratorMap_FromDefinitionMap($definitionMap, $this->definitionToConfigurator, $context);
    $legend = new CfrLegend_FromDefmap($definitionMap, $configuratorMap, $this->definitionToLabel, $this->definitionToGrouplabel);
    return $this->create($legend, $configuratorMap);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  protected function create(CfrLegendInterface $legend, IdToConfiguratorInterface $idToConfigurator) {
    return CfrFamily::create($legend, $idToConfigurator);
  }

}
