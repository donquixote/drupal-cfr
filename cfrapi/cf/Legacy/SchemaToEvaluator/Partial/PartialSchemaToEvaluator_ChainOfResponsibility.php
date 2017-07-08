<?php

namespace Donquixote\Cf\Legacy\SchemaToEvaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;

class PartialSchemaToEvaluator_ChainOfResponsibility implements PartialSchemaToEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToEvaluator\Partial\PartialSchemaToEvaluatorInterface[]
   */
  private $mappers;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToEvaluator\Partial\PartialSchemaToEvaluatorInterface[] $mappers
   */
  public function __construct(array $mappers) {
    $this->mappers = $mappers;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface|null
   */
  public function schemaGetEvaluator(CfSchemaInterface $schema) {

    foreach ($this->mappers as $mapper) {
      if (NULL !== $evaluator = $mapper->schemaGetEvaluator($schema)) {
        return $evaluator;
      }
    }

    return NULL;
  }
}
