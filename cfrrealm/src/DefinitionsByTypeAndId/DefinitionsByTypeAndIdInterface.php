<?php

namespace Drupal\cfrrealm\DefinitionsByTypeAndId;

interface DefinitionsByTypeAndIdInterface {

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId();

}
