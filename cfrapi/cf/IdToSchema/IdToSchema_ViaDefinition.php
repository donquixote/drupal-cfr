<?php

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;
use Donquixote\Cf\IdToDefinition\IdToDefinitionInterface;

class IdToSchema_ViaDefinition implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    IdToDefinitionInterface $idToDefinition,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ) {
    $this->idToDefinition = $idToDefinition;
    $this->definitionToSchema = $definitionToSchema;
    $this->context = $context;
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {

    $definition = $this->idToDefinition->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema($definition, $this->context);
    }
    catch (CfSchemaCreationException $e) {
      dpm($definition, $e->getMessage());
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
