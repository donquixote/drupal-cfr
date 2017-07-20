<?php

namespace Drupal\cfrfamily\IdMap;

use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;

class IdMap_DefinitionMap implements IdMapInterface {

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
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
