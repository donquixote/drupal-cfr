<?php

namespace Drupal\cfrfamily\IdToLabel;

use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\IdToDefinition\IdToDefinitionInterface;

class IdToLabel_ViaDefinition implements IdToLabelInterface {

  /**
   * @var \Donquixote\Cf\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @param \Donquixote\Cf\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
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
