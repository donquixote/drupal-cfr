<?php

namespace Drupal\cfrrealm\TypeToDefinitionsbyid;

interface TypeToDefinitionsbyidInterface {

  /**
   * @param string $type
   *
   * @return array[]
   *   Array of all plugin definitions for the given plugin type.
   */
  public function typeGetDefinitionsbyid($type);

}
