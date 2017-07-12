<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Mappers;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_FromDefinitionMap;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_InlineExpanded;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_DefmapDrilldown implements TypeToSchemaInterface {

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
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $definitionToCfrSchema
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param bool $withInlineChildren
   * @param bool $withTaggingDecorator
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    DefinitionToSchemaInterface $definitionToCfrSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    $withInlineChildren = TRUE,
    $withTaggingDecorator = TRUE
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->definitionToCfrSchema = $definitionToCfrSchema;
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
      $this->definitionToCfrSchema,
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
