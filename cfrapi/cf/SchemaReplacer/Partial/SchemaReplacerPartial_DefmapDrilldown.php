<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Defmap\CfSchema_DefmapInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Mappers;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_FromDefinitionMap;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_InlineExpanded;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class SchemaReplacerPartial_DefmapDrilldown implements SchemaReplacerPartialInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface
   */
  private $definitionToCfrSchema;

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
   * Creates an instance with the most common options.
   *
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param bool $withInlineChildren
   *
   * @return self
   */
  public static function create(
    TypeToDefmapInterface $typeToDefmap,
    $withInlineChildren = TRUE
  ) {
    return new self(
      $typeToDefmap,
      DefinitionToSchema_Mappers::create(),
      DefinitionToLabel::create(),
      DefinitionToLabel::createGroupLabel(),
      $withInlineChildren);
  }

  /**
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $definitionToCfrSchema
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param bool $withInlineChildren
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    DefinitionToSchemaInterface $definitionToCfrSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    $withInlineChildren = TRUE
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->definitionToCfrSchema = $definitionToCfrSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
    $this->withInlineChildren = $withInlineChildren;
  }

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    return CfSchema_DefmapInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer) {

    if (!$schema instanceof CfSchema_DefmapInterface) {
      return NULL;
    }

    $defmap = $schema->getDefinitionMap();
    $context = $schema->getContext();

    $schema = new CfSchema_Drilldown_FromDefinitionMap(
      $defmap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToCfrSchema,
      $context);

    if ($this->withInlineChildren) {
      $schema = new CfSchema_Drilldown_InlineExpanded(
        $schema,
        $defmap);
    }

    return $schema;
  }
}
