<?php

namespace Donquixote\Cf\DefinitionsByTypeAndId;

interface DefinitionsByTypeAndIdInterface {

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId();

}
