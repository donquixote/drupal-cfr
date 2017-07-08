<?php

namespace Drupal\cfrrealm\TypeToDrilldownSchema;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToDrilldownSchema_ViaDefmap implements TypeToDrilldownSchemaInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface
   */
  private $defmapToDrilldownSchema;

  /**
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface $defmapToDrilldownSchema
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    DefmapToDrilldownSchemaInterface $defmapToDrilldownSchema
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->defmapToDrilldownSchema = $defmapToDrilldownSchema;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function typeGetDrilldownSchema($type, CfrContextInterface $context = NULL) {
    $defmap = $this->typeToDefmap->typeGetDefmap($type);
    return $this->defmapToDrilldownSchema->defmapGetDrilldownSchema(
      $defmap,
      $context);
  }
}
