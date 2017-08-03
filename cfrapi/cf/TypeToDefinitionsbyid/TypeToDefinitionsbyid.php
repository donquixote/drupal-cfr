<?php

namespace Donquixote\Cf\TypeToDefinitionsbyid;

use Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;

class TypeToDefinitionsbyid implements TypeToDefinitionsbyidInterface {

  /**
   * @var \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @var array[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
   */
  public function __construct(DefinitionsByTypeAndIdInterface $definitionsByTypeAndId) {
    $this->definitionsByTypeAndId = $definitionsByTypeAndId;
  }

  /**
   * @param string $type
   *
   * @return array[]
   *   Array of all plugin definitions for the given plugin type.
   */
  public function typeGetDefinitionsbyid($type) {
    if (NULL === $this->buffer) {
      $this->buffer = $this->definitionsByTypeAndId->getDefinitionsByTypeAndId();
    }
    return isset($this->buffer[$type])
      ? $this->buffer[$type]
      : [];
  }
}
