<?php

namespace Drupal\cfrfamily\DefmapToCfrFamily;

use Drupal\cfrfamily\CfrFamily\CfrFamily;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class DefmapToCfrFamily_InlineExpanded extends DefmapToCfrFamily {

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  protected function create(CfrLegendInterface $legend, IdToConfiguratorInterface $idToConfigurator) {
    return CfrFamily::createExpanded($legend, $idToConfigurator);
  }
}
