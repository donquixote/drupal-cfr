<?php

namespace Drupal\cfrrealm\TypeToCfrFamily;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamilyInterface;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToCfrFamily_ViaDefmap implements TypeToCfrFamilyInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamilyInterface
   */
  private $defmapToCfrFamily;

  /**
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamilyInterface $defmapToCfrFamily
   */
  function __construct(TypeToDefmapInterface $typeToDefmap, DefmapToCfrFamilyInterface $defmapToCfrFamily) {
    $this->typeToDefmap = $typeToDefmap;
    $this->defmapToCfrFamily = $defmapToCfrFamily;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  function typeGetCfrFamily($type, CfrContextInterface $context = NULL) {
    $defmap = $this->typeToDefmap->typeGetDefmap($type);
    return $this->defmapToCfrFamily->defmapGetCfrFamily($defmap, $context);
  }
}
