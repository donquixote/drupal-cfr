<?php

namespace Drupal\cfrfamily\IdToLegend;

interface IdToLegendInterface {

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\Legend\LegendInterface|null
   */
  function idGetLegend($id);

}
