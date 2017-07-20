<?php

namespace Drupal\cfrfamily\CfrLegend;

use Drupal\cfrapi\PossiblyOptionless\PossiblyOptionlessInterface;
use Drupal\cfrfamily\CfrLegendItem\CfrLegendItem;
use Drupal\cfrfamily\CfrLegendItem\CfrLegendItem_Parent;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class CfrLegend_FromDefmap implements CfrLegendInterface {

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  public function __construct(
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
  public function getLegendItems($depth = 0) {
    static $rec = 0;
    if ($rec > 4) {
      throw new \RuntimeException('Possibly unlimited recursion detected.');
    }
    ++$rec;
    $items = [];
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
  public function idGetLegendItem($id) {
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
  public function idIsKnown($id) {
    return 1
      && NULL !== $this->definitionMap->idGetDefinition($id)
      && NULL !== $this->idToConfigurator->idGetConfigurator($id);
  }

  /**
   * @param string $id
   * @param array $definition
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface|null
   */
  private function idDefinitionGetLegendItem($id, array $definition) {

    if (NULL === $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return NULL;
    }

    $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
    $groupLabel = $this->definitionGetGroupLabel($definition);

    if ($configurator instanceof PossiblyOptionlessInterface && $configurator->isOptionless()) {
      return new CfrLegendItem($label, $groupLabel, $configurator);
    }

    if (1
      && array_key_exists('inline', $definition)
      && TRUE === $definition['inline']
      && $configurator instanceof CfrLegendProviderInterface
      && NULL !== $innerCfrLegend = $configurator->getCfrLegend()
    ) {
      return new CfrLegendItem_Parent($label, $groupLabel, $configurator, $innerCfrLegend);
    }

    return new CfrLegendItem($label, $groupLabel, $configurator);
  }
}
