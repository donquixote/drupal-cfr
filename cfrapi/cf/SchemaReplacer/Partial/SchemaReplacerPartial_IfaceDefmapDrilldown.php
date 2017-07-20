<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromDefinitionMap;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal_InlineExpanded;
use Donquixote\Cf\Schema\Id\CfSchema_Id_DefmapKey;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

/**
 * A replacer that creates a drilldown schema for a given interface schema,
 * based on definitions registered somewhere.
 */
class SchemaReplacerPartial_IfaceDefmapDrilldown implements SchemaReplacerPartialInterface {

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
   * @var bool
   */
  private $withInlineChildren;

  /**
   * @var bool
   */
  private $withTaggingDecorator;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * Creates an instance with the most common options.
   *
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param bool $withInlineChildren
   * @param bool $withTaggingDecorator
   *
   * @return self
   */
  public static function create(
    TypeToDefmapInterface $typeToDefmap,
    $withInlineChildren = TRUE,
    $withTaggingDecorator = TRUE
  ) {
    return new self(
      $typeToDefmap,
      DefinitionToSchema_Mappers::create(),
      DefinitionToLabel::create(),
      DefinitionToLabel::createGroupLabel(),
      $withInlineChildren,
      $withTaggingDecorator);
  }

  /**
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param bool $withInlineChildren
   * @param bool $withTaggingDecorator
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    DefinitionToSchemaInterface $definitionToSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    $withInlineChildren = TRUE,
    $withTaggingDecorator = TRUE
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->definitionToSchema = $definitionToSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
    $this->withInlineChildren = $withInlineChildren;
    $this->withTaggingDecorator = $withTaggingDecorator;
  }

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    // Accepts any schema.
    return CfSchema_IfaceWithContextInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer) {

    if (!$schema instanceof CfSchema_IfaceWithContextInterface) {
      return NULL;
    }

    $k = $schema->getCacheId();

    // The value NULL does not occur, so isset() is safe.
    return isset($this->schemas[$k])
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->schemaDoGetReplacement($schema);
  }

  /**
   * @param \Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private function schemaDoGetReplacement(CfSchema_IfaceWithContextInterface $ifaceSchema) {

    $type = $ifaceSchema->getInterface();
    $context = $ifaceSchema->getContext();

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    $schema = new CfSchema_Drilldown_FromDefinitionMap(
      $defmap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);

    if ($this->withInlineChildren) {

      $inlineIdsLookup = new CfSchema_Id_DefmapKey(
        $defmap,
        'inline');

      $schema = CfSchema_DrilldownVal_InlineExpanded::createOrSame(
        $schema,
        $inlineIdsLookup);
    }

    if ($this->withTaggingDecorator) {

      $schema = new CfSchema_Neutral_IfaceTransformed(
        $schema,
        $type,
        $context);
    }

    return $schema;
  }
}
