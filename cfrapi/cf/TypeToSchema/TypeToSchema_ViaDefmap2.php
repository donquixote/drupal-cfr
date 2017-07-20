<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromDefinitionMap;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_ViaDefmap2 implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    DefinitionToSchemaInterface $definitionToSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->definitionToSchema = $definitionToSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetSchema($type, CfContextInterface $context = NULL) {

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    return new CfSchema_Drilldown_FromDefinitionMap(
      $defmap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);
  }
}
