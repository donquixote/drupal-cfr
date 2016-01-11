<?php

namespace Drupal\cfrrealm\TypeToDefmap;

class TypeToDefmap_Buffer implements TypeToDefmapInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface[]
   */
  private $definitionMaps = array();

  /**
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $decorated
   */
  function __construct(TypeToDefmapInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   *
   * @return \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  function typeGetDefmap($type) {
    return array_key_exists($type, $this->definitionMaps)
      ? $this->definitionMaps[$type]
      : $this->definitionMaps[$type] = $this->decorated->typeGetDefmap($type);
  }
}
