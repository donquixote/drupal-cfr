<?php

namespace Drupal\cfrfamily\Legend;

use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Inline\InlineConfiguratorInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;
use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrapi\SometimesConfigurable\PossiblyUnconfigurableInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\IdToLegend\IdToLegendInterface;

class Legend_FromDefmapAdvanced implements LegendInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @var \Drupal\cfrfamily\IdToLegend\IdToLegendInterface
   */
  private $idToLegend;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdToLegend\IdToLegendInterface $idToLegend
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  function __construct(
    DefinitionMapInterface $definitionMap,
    IdToConfiguratorInterface $idToConfigurator,
    IdToLegendInterface $idToLegend,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->idToConfigurator = $idToConfigurator;
    $this->idToLegend = $idToLegend;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGroupLabel;
  }

  /**
   * @return mixed[]
   */
  function getSelectOptions() {
    $options = array();
    foreach ($this->definitionMap->getDefinitionsById() as $id => $definition) {
      $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
      $configurator = $this->idToConfigurator->idGetConfigurator($id);
      if (0
        || !$configurator instanceof ConfiguratorInterface
        || $configurator instanceof BrokenConfiguratorInterface
      ) {
        continue;
      }
      if (1
        && $configurator instanceof InlineConfiguratorInterface
        && NULL !== ($nestedOptions = $configurator->getInlineOptions())
      ) {
        foreach ($nestedOptions as $nestedId => $nestedLabel) {
          $options[$label][$id . '/' . $nestedId] = $nestedLabel;
        }
      }
      if (0
        || !$configurator instanceof PossiblyUnconfigurableInterface
        || $configurator->isConfigurable()
      ) {
        $label .= '…';
      }
      if (1
        && NULL !== $this->definitionToGrouplabel
        && NULL !== ($groupLabel = $this->definitionToGrouplabel->definitionGetLabel($definition, NULL))
        && '' !== $groupLabel
      ) {
        $options[$groupLabel][$id] = $label;
      }
      else {
        $options[$id] = $label;
      }
    }
    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  function idGetLabel($id) {
    $definition = $this->definitionMap->idGetDefinition($id);
    if (NULL === $definition) {
      return '- ' . t('Unknown') . ' -';
    }
    return $this->definitionToLabel->definitionGetLabel($definition, $id);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id) {
    $definition = $this->definitionMap->idGetDefinition($id);
    return NULL !== $definition;
  }
}
