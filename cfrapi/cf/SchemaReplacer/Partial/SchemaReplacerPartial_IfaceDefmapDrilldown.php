<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Mappers;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_FromDefinitionMap;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_InlineExpanded;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

/**
 * A replacer that creates a drilldown schema for a given interface schema,
 * based on definitions registered somewhere.
 */
class SchemaReplacerPartial_IfaceDefmapDrilldown implements SchemaReplacerPartialInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
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
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
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
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
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
      $schema = new CfSchema_Drilldown_InlineExpanded(
        $schema,
        $defmap);
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
