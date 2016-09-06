<?php

namespace Drupal\cfrfamily\IdToLabel;

use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface;

class IdToLabel_ViaDefinition implements IdToLabelInterface {

  /**
   * @var \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @param \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   */
  public function __construct(IdToDefinitionInterface $idToDefinition, DefinitionToLabelInterface $definitionToLabel) {
    $this->idToDefinition = $idToDefinition;
    $this->definitionToLabel = $definitionToLabel;
  }

  /**
   * @param string $id
   * @param string|null $else
   *
   * @return string|null
   */
  public function idGetLabel($id, $else = NULL) {

    if (NULL === $definition = $this->idToDefinition->idGetDefinition($id)) {
      return NULL;
    }

    return $this->definitionToLabel->definitionGetLabel($definition, NULL);
  }
}
