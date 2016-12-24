<?php

namespace Drupal\cfrfamily\CfrFamilyContainer;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\CfrLegend\CfrLegend_FromDefmap;
use Drupal\cfrfamily\Configurator\Composite\Configurator_CfrLegend;
use Drupal\cfrfamily\IdToConfigurator\IdToConfigurator_ViaDefinition;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValue_IdToConfigurator;

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
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @see $configurator
   */
  protected function get_configurator() {
    $idConfToValue = new IdConfToValue_IdToConfigurator($this->idToConfigurator);
    return new Configurator_CfrLegend(TRUE, $this->cfrLegend, $idConfToValue);
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @see $optionalConfigurator
   */
  protected function get_optionalConfigurator() {
    $idConfToValue = new IdConfToValue_IdToConfigurator($this->idToConfigurator);
    return new Configurator_CfrLegend(FALSE, $this->cfrLegend, $idConfToValue);
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   *
   * @see $cfrLegend
   */
  protected function get_cfrLegend() {
    return new CfrLegend_FromDefmap(
      $this->definitionMap,
      $this->idToConfigurator,
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
    return new IdToConfigurator_ViaDefinition(
      $this->definitionMap,
      $this->definitionToConfigurator,
      $this->context);
  }
}
