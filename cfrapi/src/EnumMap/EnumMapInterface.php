<?php

namespace Drupal\cfrapi\EnumMap;

use Drupal\cfrapi\Legend\LegendInterface;

interface EnumMapInterface extends LegendInterface {

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  function idIsKnown($id);

}
