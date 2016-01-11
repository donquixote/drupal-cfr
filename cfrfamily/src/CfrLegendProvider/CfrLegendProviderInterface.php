<?php

namespace Drupal\cfrfamily\CfrLegendProvider;

interface CfrLegendProviderInterface {

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  function getCfrLegend();

}
