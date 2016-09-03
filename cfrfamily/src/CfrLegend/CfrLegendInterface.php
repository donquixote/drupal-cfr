<?php

namespace Drupal\cfrfamily\CfrLegend;

interface CfrLegendInterface {

  /**
   * @param int $depth
   *   This parameter is only relevant for legends with inline expansion.
   *   See
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface[]
   *   Format: $[$pluginId] = $cfrLegendItem
   */
  public function getLegendItems($depth = 0);

  /**
   * @param string $id
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface|null
   */
  public function idGetLegendItem($id);

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id);

}
