<?php

namespace Drupal\cfrfamily\IdToDefinition;

interface IdToDefinitionInterface {

  /**
   * @param string $id
   *
   * @return array
   *   A configurator definition array.
   */
  public function idGetDefinition($id);

}
