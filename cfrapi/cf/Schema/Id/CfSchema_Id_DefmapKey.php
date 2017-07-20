<?php

namespace Donquixote\Cf\Schema\Id;

use Donquixote\Cf\IdToDefinition\IdToDefinitionInterface;

class CfSchema_Id_DefmapKey implements CfSchema_IdInterface {

  /**
   * @var \Donquixote\Cf\IdToDefinition\IdToDefinitionInterface
   */
  private $definitionMap;

  /**
   * @var string
   */
  private $key;

  /**
   * @param \Donquixote\Cf\IdToDefinition\IdToDefinitionInterface $definitionMap
   * @param string $key
   */
  public function __construct(IdToDefinitionInterface $definitionMap, $key) {
    $this->definitionMap = $definitionMap;
    $this->key = $key;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return FALSE;
    }

    return !empty($definition[$this->key]);
  }
}
