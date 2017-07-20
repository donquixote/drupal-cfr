<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_ViaDefmap implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Donquixote\Cf\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface
   */
  private $defmapToDrilldownSchema;

  /**
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Donquixote\Cf\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface $defmapToDrilldownSchema
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
