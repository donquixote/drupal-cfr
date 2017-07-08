<?php

namespace Drupal\cfrfamily\IdToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\SchemaCreationException;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface;
use Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface;

class IdToCfrSchema_ViaDefinition implements IdToCfrSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface
   */
  private $definitionToCfrSchema;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface $definitionToCfrSchema
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   */
  public function __construct(
    IdToDefinitionInterface $idToDefinition,
    DefinitionToCfrSchemaInterface $definitionToCfrSchema,
    CfrContextInterface $context = NULL
  ) {
    $this->idToDefinition = $idToDefinition;
    $this->definitionToCfrSchema = $definitionToCfrSchema;
    $this->context = $context;
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetCfrSchema($id) {

    $definition = $this->idToDefinition->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    try {
      return $this->definitionToCfrSchema->definitionGetCfrSchema($definition, $this->context);
    }
    catch (SchemaCreationException $e) {
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
