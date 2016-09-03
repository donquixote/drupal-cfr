<?php

namespace Drupal\cfrapi\EnumMap;

use Drupal\cfrapi\Legend\LegendInterface;

interface EnumMapInterface extends LegendInterface {

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id);

}
