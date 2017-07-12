<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema;

use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

class DefinitionToSchema_Replacer implements DefinitionToSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $decorated
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   */
  public function __construct(
    DefinitionToSchemaInterface $decorated,
    SchemaReplacerInterface $replacer
  ) {
    $this->decorated = $decorated;
    $this->replacer = $replacer;
  }

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function definitionGetSchema(
    array $definition,
    CfrContextInterface $context = NULL
  ) {
    $cfrSchema = $this->decorated->definitionGetSchema(
      $definition,
      $context);

    if (NULL !== $replacement = $this->replacer->schemaGetReplacement(
      $cfrSchema)
    ) {
      $cfrSchema = $replacement;
    }

    return $cfrSchema;
  }
}
