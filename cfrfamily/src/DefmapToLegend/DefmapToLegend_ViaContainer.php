<?php

namespace Drupal\cfrfamily\DefmapToLegend;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface;

class DefmapToLegend_ViaContainer implements DefmapToLegendInterface {

  /**
   * @var \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface
   */
  private $defmapToContainer;

  /**
   * @param \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface $defmapToContainer
   */
  public function __construct(DefmapToContainerInterface $defmapToContainer) {
    $this->defmapToContainer = $defmapToContainer;
  }

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Legend\LegendInterface
   */
  public function defmapGetLegend(DefinitionMapInterface $definitionMap, CfrContextInterface $context) {
    return $this->defmapToContainer->defmapGetContainer($definitionMap, $context);
  }
}
