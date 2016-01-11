<?php

namespace Drupal\cfrapi\StructuredLegend;

interface StructuredLegendInterface {

  /**
   * @param int $depth
   *   This parameter is only relevant for legends with inline expansion.
   *   See
   *
   * @return \Drupal\cfrapi\LegendItem\LegendItemInterface[]
   */
  function getLegendItems($depth = 0);

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\LegendItem\LegendItemInterface|null
   */
  function idGetLegendItem($id);

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id);

}
