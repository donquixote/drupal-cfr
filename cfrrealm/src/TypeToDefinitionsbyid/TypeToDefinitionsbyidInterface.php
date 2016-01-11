<?php

namespace Drupal\cfrrealm\TypeToDefinitionsbyid;

interface TypeToDefinitionsbyidInterface {

  /**
   * @param string $type
   *
   * @return \Drupal\cfrfamily\DefinitionsById\DefinitionsByIdInterface
   */
  function typeGetDefinitionsbyid($type);

}
