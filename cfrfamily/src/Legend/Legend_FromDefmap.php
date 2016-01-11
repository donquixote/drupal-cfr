<?php

namespace Drupal\cfrfamily\Legend;

use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;
use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrapi\SometimesConfigurable\PossiblyUnconfigurableInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;

class Legend_FromDefmap implements LegendInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

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
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  function __construct(
    DefinitionMapInterface $definitionMap,
    IdToConfiguratorInterface $idToConfigurator,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->idToConfigurator = $idToConfigurator;
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
      if (!$configurator instanceof ConfiguratorInterface || $configurator instanceof BrokenConfiguratorInterface) {
        continue;
      }
      if (!$configurator instanceof PossiblyUnconfigurableInterface || $configurator->isConfigurable()) {
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
