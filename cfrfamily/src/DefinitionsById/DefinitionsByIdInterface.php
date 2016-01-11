<?php

namespace Drupal\cfrfamily\DefinitionsById;

interface DefinitionsByIdInterface {

  /**
   * @return array[]
   *   Array of all configurator definitions for this plugin type.
   */
  function getDefinitionsById();

}
