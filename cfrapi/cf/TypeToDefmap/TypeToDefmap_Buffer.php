<?php

namespace Donquixote\Cf\TypeToDefmap;

class TypeToDefmap_Buffer implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface[]
   */
  private $definitionMaps = [];

  /**
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $decorated
   */
  public function __construct(TypeToDefmapInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   *
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap($type) {
    return array_key_exists($type, $this->definitionMaps)
      ? $this->definitionMaps[$type]
      : $this->definitionMaps[$type] = $this->decorated->typeGetDefmap($type);
  }
}
