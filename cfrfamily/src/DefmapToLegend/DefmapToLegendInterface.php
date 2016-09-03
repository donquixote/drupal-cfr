<?php

namespace Drupal\cfrfamily\DefmapToLegend;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

interface DefmapToLegendInterface {

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Legend\LegendInterface
   */
  public function defmapGetLegend(DefinitionMapInterface $definitionMap, CfrContextInterface $context);

}
