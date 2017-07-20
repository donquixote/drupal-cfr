<?php

namespace Donquixote\Cf\IdToDefinition;

interface IdToDefinitionInterface {

  /**
   * @param string $id
   *
   * @return array|null
   *   A configurator definition array.
   */
  public function idGetDefinition($id);

}
