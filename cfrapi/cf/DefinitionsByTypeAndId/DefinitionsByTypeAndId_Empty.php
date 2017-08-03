<?php

namespace Donquixote\Cf\DefinitionsByTypeAndId;

class DefinitionsByTypeAndId_Empty implements DefinitionsByTypeAndIdInterface {

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId() {
    return [];
  }
}
