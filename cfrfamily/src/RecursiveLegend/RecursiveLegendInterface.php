<?php

namespace Drupal\cfrfamily\RecursiveLegend;

use Drupal\cfrapi\EnumMap\EnumMapInterface;

interface RecursiveLegendInterface extends EnumMapInterface {

  /**
   * @param int $maxRecursionDepth
   *
   * @return mixed[]|string[]|string[][]
   */
  public function getSelectOptions($maxRecursionDepth = 1);

}
