<?php

namespace Drupal\cfrfamily\CfrFamilyContainer;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Configurator\Composite\Configurator_Composite;
use Drupal\cfrapi\Configurator\Composite\Configurator_CompositeOptional;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\CfrLegend\CfrLegend_FromDefmap;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMap_FromDefinitionMap;
use Drupal\cfrfamily\ConfToForm\ConfToForm_CfrLegend;
use Drupal\cfrfamily\ConfToSummary\ConfToSummary_CfrLegend;
use Drupal\cfrfamily\ConfToValue\ConfToValue_CfrMap;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;

class CfrFamilyContainer_FromDefmap extends CfrFamilyContainerBase {

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   */
  private $definitionToConfigurator;

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface $definitionToConfigurator
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   */
  public function __construct(
    DefinitionToConfiguratorInterface $definitionToConfigurator,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    DefinitionMapInterface $definitionMap,
    CfrContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->context = $context;
    $this->definitionToConfigurator = $definitionToConfigurator;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * @return \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMap_FromDefinitionMap
   *
   * @see $configuratorMap
   */
  protected function get_configuratorMap() {
    return new ConfiguratorMap_FromDefinitionMap(
      $this->definitionMap,
      $this->definitionToConfigurator,
      $this->context);
  }

  /**
   * @return \Drupal\cfrapi\Configurator\Composite\Configurator_Composite
   *
   * @see $configurator
   */
  protected function get_configurator() {
    $confToForm = ConfToForm_CfrLegend::createRequired($this->cfrLegend);
    $confToSummary = ConfToSummary_CfrLegend::createRequired($this->cfrLegend);
    $confToValue = ConfToValue_CfrMap::createRequired($this->configuratorMap);
    return new Configurator_Composite($confToForm, $confToSummary, $confToValue);
    # return (new Configurator_IdConfAdvanced($this->legend, $this->configuratorMap));
  }

  /**
   * @return \Drupal\cfrapi\Configurator\Composite\Configurator_CompositeOptional
   *
   * @see $optionalConfigurator
   */
  protected function get_optionalConfigurator() {
    $confToForm = ConfToForm_CfrLegend::createOptional($this->cfrLegend);
    $confToSummary = ConfToSummary_CfrLegend::createOptional($this->cfrLegend);
    $confToValue = ConfToValue_CfrMap::createOptional($this->configuratorMap);
    return new Configurator_CompositeOptional($confToForm, $confToSummary, $confToValue, $this->confEmptyness);
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   *
   * @see $cfrLegend
   */
  protected function get_cfrLegend() {
    return new CfrLegend_FromDefmap(
      $this->definitionMap,
      $this->configuratorMap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel);
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   *
   * @see $confEmptyness
   */
  protected function get_confEmptyness() {
    return new ConfEmptyness_Key('id');
  }

  /**
   * @return \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   *
   * @see $idToConfigurator
   */
  protected function get_idToConfigurator() {
    return $this->configuratorMap;
  }
}
