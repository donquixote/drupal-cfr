<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_DefinitionInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;

class SchemaReplacerPartial_Definition implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
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
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
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
      dpm($schema->getDefinition(), $e->getMessage());
      // @todo Allow throwing exceptions? Log the problem somewhere? BrokenSchema?
      return NULL;
    }

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $schema = $replacement;
    }

    return $schema;
  }
}
