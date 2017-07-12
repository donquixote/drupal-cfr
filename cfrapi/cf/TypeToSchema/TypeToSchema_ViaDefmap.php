<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
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
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetSchema($type, CfContextInterface $context = NULL) {

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    return $this->defmapToDrilldownSchema->defmapGetDrilldownSchema(
      $defmap,
      $context);
  }
}
