<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_DefinitionInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Mappers;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface;

class SchemaReplacerPartial_Definition implements SchemaReplacerPartialInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @return self
   */
  public static function create() {
    $definitionToSchema = DefinitionToSchema_Mappers::create();
    return new self($definitionToSchema);
  }

  /**
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $definitionToSchema
   */
  public function __construct(DefinitionToSchemaInterface $definitionToSchema) {
    $this->definitionToSchema = $definitionToSchema;
  }

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    return CfSchema_DefinitionInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer) {

    if (!$schema instanceof CfSchema_DefinitionInterface) {
      return NULL;
    }

    try {
      $schema = $this->definitionToSchema->definitionGetSchema(
        $schema->getDefinition(),
        $schema->getContext());
    }
    catch (\Exception $e) {
      // @todo Allow throwing exceptions? Log the problem somewhere? BrokenSchema?
      return NULL;
    }

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }
}
