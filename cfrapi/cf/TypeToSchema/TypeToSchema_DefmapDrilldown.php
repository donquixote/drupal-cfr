<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal_InlineExpanded;
use Donquixote\Cf\Schema\Id\CfSchema_Id_DefmapKey;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromDefinitionMap;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_DefmapDrilldown implements TypeToSchemaInterface {

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
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @var bool
   */
  private $withTaggingDecorator;

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
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetSchema($type, CfContextInterface $context = NULL) {

    $k = $type;
    if (NULL !== $context) {
      $k .= '::' . $context->getMachineName();
    }

    // The value NULL does not occur, so isset() is safe.
    return isset($this->schemas[$k])
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->typeDoGetSchema($type, $context);
  }

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private function typeDoGetSchema($type, CfContextInterface $context = NULL) {

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
