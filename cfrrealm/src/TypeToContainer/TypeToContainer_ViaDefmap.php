<?php

namespace Drupal\cfrrealm\TypeToContainer;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface;

use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToContainer_ViaDefmap implements TypeToContainerInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface
   */
  private $defmapToContainer;

  /**
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface $defmapToContainer
   */
  public function __construct(TypeToDefmapInterface $typeToDefmap, DefmapToContainerInterface $defmapToContainer) {
    $this->typeToDefmap = $typeToDefmap;
    $this->defmapToContainer = $defmapToContainer;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface
   */
  public function typeGetContainer($type, CfrContextInterface $context = NULL) {
    $definitionMap = $this->typeToDefmap->typeGetDefmap($type);
    return $this->defmapToContainer->defmapGetContainer($definitionMap, $context);
  }
}
