<?php

namespace Donquixote\Cf\TypeToDefmap;

interface TypeToDefmapInterface {

  /**
   * @param string $type
   *
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap($type);

}
