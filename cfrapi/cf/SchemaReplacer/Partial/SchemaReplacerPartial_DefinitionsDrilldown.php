<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Definitions\CfSchema_DefinitionsInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromDefinitions;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal_InlineExpanded;
use Donquixote\Cf\Schema\Id\CfSchema_Id_DefinitionsKey;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_DefinitionsDrilldown implements SchemaReplacerPartialInterface {

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
   * Creates an instance with the most common options.
   *
   * @param bool $withInlineChildren
   *
   * @return self
   */
  public static function create($withInlineChildren = TRUE) {
    return new self(
      DefinitionToSchema_Mappers::create(),
      DefinitionToLabel::create(),
      DefinitionToLabel::createGroupLabel(),
      $withInlineChildren);
  }

  /**
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param bool $withInlineChildren
   */
  public function __construct(
    DefinitionToSchemaInterface $definitionToSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    $withInlineChildren = TRUE
  ) {
    $this->definitionToSchema = $definitionToSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
    $this->withInlineChildren = $withInlineChildren;
  }

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    return CfSchema_DefinitionsInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $original
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $original, SchemaReplacerInterface $replacer) {

    if (!$original instanceof CfSchema_DefinitionsInterface) {
      return NULL;
    }

    $definitions = $original->getDefinitions();
    $context = $original->getContext();

    $schema = new CfSchema_Drilldown_FromDefinitions(
      $definitions,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);

    if ($this->withInlineChildren) {

      $inlineIdsLookup = new CfSchema_Id_DefinitionsKey(
        $definitions,
        'inline');

      $schema = CfSchema_DrilldownVal_InlineExpanded::createOrSame(
        $schema,
        $inlineIdsLookup);
    }

    return $schema;
  }
}
