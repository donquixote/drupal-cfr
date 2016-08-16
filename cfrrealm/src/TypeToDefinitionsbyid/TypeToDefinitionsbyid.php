<?php

namespace Drupal\cfrrealm\TypeToDefinitionsbyid;

use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;

class TypeToDefinitionsbyid implements TypeToDefinitionsbyidInterface {

  /**
   * @var \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @var array[][]|null
   */
  private $buffer;

  /**
   * @param \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
   */
  function __construct(DefinitionsByTypeAndIdInterface $definitionsByTypeAndId) {
    $this->definitionsByTypeAndId = $definitionsByTypeAndId;
  }

  /**
   * @param string $type
   *
   * @return array[]
   *   Array of all plugin definitions for the given plugin type.
   */
  function typeGetDefinitionsbyid($type) {
    if (NULL === $this->buffer) {
      $this->buffer = $this->definitionsByTypeAndId->getDefinitionsByTypeAndId();
    }
    return isset($this->buffer[$type])
      ? $this->buffer[$type]
      : array();
  }
}
