<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfrContextInterface;
use Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_ViaDefmap implements TypeToSchemaInterface {

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
   * @param \Donquixote\Cf\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfrContextInterface $context = NULL) {
    $defmap = $this->typeToDefmap->typeGetDefmap($type);
    return $this->defmapToDrilldownSchema->defmapGetDrilldownSchema(
      $defmap,
      $context);
  }
}
