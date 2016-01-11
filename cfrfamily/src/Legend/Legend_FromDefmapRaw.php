<?php

namespace Drupal\cfrfamily\Legend;

use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;

class Legend_FromDefmapRaw implements LegendInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

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
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel = NULL
  ) {
    $this->definitionMap = $definitionMap;
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
