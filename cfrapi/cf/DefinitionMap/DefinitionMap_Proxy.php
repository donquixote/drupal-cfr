<?php

namespace Donquixote\Cf\DefinitionMap;

use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

class DefinitionMap_Proxy implements DefinitionMapInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var string
   */
  private $type;

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface|null
   */
  private $defmap;

  /**
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param string $type
   */
  public function __construct(TypeToDefmapInterface $typeToDefmap, $type) {
    $this->typeToDefmap = $typeToDefmap;
    $this->type = $type;
  }

  /**
   * @param string $id
   *
   * @return array|null
   */
  public function idGetDefinition($id) {
    return $this->getDefmap()->idGetDefinition($id);
  }

  /**
   * @return array[]
   */
  public function getDefinitionsById() {
    return $this->getDefmap()->getDefinitionsById();
  }

  /**
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface|null
   */
  private function getDefmap() {
    return NULL !== $this->defmap
      ? $this->defmap
      : $this->defmap = $this->typeToDefmap->typeGetDefmap($this->type);
  }
}
