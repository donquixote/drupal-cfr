<?php

namespace Drupal\cfrfamily\CfrLegend;

use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SometimesConfigurable\PossiblyUnconfigurableInterface;
use Drupal\cfrfamily\CfrLegendItem\CfrLegendItem;
use Drupal\cfrfamily\CfrLegendItem\CfrLegendItem_Parent;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class CfrLegend_FromDefmap implements CfrLegendInterface {

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
  private $definitionToGroupLabel;

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
    DefinitionToLabelInterface $definitionToGroupLabel
  ) {
    $this->definitionMap = $definitionMap;
    $this->idToConfigurator = $idToConfigurator;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
  }

  /**
   * @param int $depth
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface[]
   */
  function getLegendItems($depth = 0) {
    static $rec = 0;
    if ($rec > 4) {
      throw new \RuntimeException('Possibly unlimited recursion detected.');
    }
    ++$rec;
    $items = array();
    foreach ($this->definitionMap->getDefinitionsById() as $id => $definition) {
      $legendItem = $this->idDefinitionGetLegendItem($id, $definition);
      if (NULL !== $legendItem) {
        $items[$id] = $legendItem;
      }
    }
    --$rec;
    return $items;
  }

  /**
   * @param array $definition
   *
   * @return null|string
   */
  private function definitionGetGroupLabel(array $definition) {
    if (NULL === $this->definitionToGroupLabel) {
      return NULL;
    }
    $groupLabel = $this->definitionToGroupLabel->definitionGetLabel($definition, NULL);
    if ('' === $groupLabel) {
      return NULL;
    }
    return $groupLabel;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface|null
   */
  function idGetLegendItem($id) {
    $definition = $this->definitionMap->idGetDefinition($id);
    if (NULL === $definition) {
      return NULL;
    }
    return $this->idDefinitionGetLegendItem($id, $definition);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id) {
    $definition = $this->definitionMap->idGetDefinition($id);
    if (NULL === $definition) {
      return FALSE;
    }
    $configurator = $this->idToConfigurator->idGetConfigurator($id);
    if (!$configurator instanceof ConfiguratorInterface || $configurator instanceof BrokenConfiguratorInterface) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * @param string $id
   * @param array $definition
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface|null
   */
  private function idDefinitionGetLegendItem($id, array $definition) {
    $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
    $groupLabel = $this->definitionGetGroupLabel($definition);
    $configurator = $this->idToConfigurator->idGetConfigurator($id);
    if (!$configurator instanceof ConfiguratorInterface || $configurator instanceof BrokenConfiguratorInterface) {
      return NULL;
    }
    if (!$configurator instanceof PossiblyUnconfigurableInterface || $configurator->isConfigurable()) {
      $label .= '…';
      if (1
        and array_key_exists('inline', $definition)
        and TRUE === $definition['inline']
        and $configurator instanceof CfrLegendProviderInterface
        and NULL !== $structuredLegend = $configurator->getCfrLegend()
      ) {
        return new CfrLegendItem_Parent($label, $groupLabel, $configurator, $structuredLegend);
      }
      else {
        return new CfrLegendItem($label, $groupLabel, $configurator);
      }
    }
    else {
      return new CfrLegendItem($label, $groupLabel, $configurator);
    }
  }
}
