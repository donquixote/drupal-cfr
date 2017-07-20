<?php

namespace Donquixote\Cf\DefinitionsById;

interface DefinitionsByIdInterface {

  /**
   * @return array[]
   *   Array of all configurator definitions for this plugin type.
   */
  public function getDefinitionsById();

}
