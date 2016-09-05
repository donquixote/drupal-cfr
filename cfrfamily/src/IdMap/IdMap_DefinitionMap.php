<?php

namespace Drupal\cfrfamily\IdMap;

use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

class IdMap_DefinitionMap implements IdMapInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   */
  public function __construct(DefinitionMapInterface $definitionMap) {
    $this->definitionMap = $definitionMap;
  }

  /**
   * @return string[]
   */
  public function getIds() {
    return array_keys($this->definitionMap->getDefinitionsById());
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return NULL !== $this->definitionMap->idGetDefinition($id);
  }
}
