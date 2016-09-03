<?php

namespace Drupal\cfrrealm\TypeToDefmap;

interface TypeToDefmapInterface {

  /**
   * @param string $type
   *
   * @return \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap($type);

}
