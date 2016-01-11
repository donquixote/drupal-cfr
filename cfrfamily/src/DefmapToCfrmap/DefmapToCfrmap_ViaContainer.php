<?php

namespace Drupal\cfrfamily\DefmapToCfrmap;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

use Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface;

class DefmapToCfrmap_ViaContainer implements DefmapToCfrmapInterface {

  /**
   * @var \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface
   */
  private $defmapToContainer;

  /**
   * @param \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface $defmapToContainer
   */
  function __construct(DefmapToContainerInterface $defmapToContainer) {
    $this->defmapToContainer = $defmapToContainer;
  }

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface
   */
  function defmapGetCfrmap(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL) {
    return $this->defmapToContainer->defmapGetContainer($definitionMap, $context)->configuratorMap;
  }
}
