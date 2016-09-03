<?php

namespace Drupal\cfrfamily\CfrLegendProvider;

interface CfrLegendProviderInterface {

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  public function getCfrLegend();

}
