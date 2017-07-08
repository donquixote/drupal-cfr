<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema;

use Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

class DefinitionToCfrSchema_Replacer implements DefinitionToCfrSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface $decorated
   * @param \Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface $replacer
   */
  public function __construct(
    DefinitionToCfrSchemaInterface $decorated,
    CfrSchemaReplacerInterface $replacer
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
  public function definitionGetCfrSchema(
    array $definition,
    CfrContextInterface $context = NULL
  ) {
    $cfrSchema = $this->decorated->definitionGetCfrSchema(
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
