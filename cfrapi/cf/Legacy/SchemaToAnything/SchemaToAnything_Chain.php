<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything;

use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnything_Chain implements SchemaToAnythingInterface {

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
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {

    foreach ($this->mappers as $mapper) {
      if (NULL !== $candidate = $mapper->schemaGetEvaluator($schema)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }
}
