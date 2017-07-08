<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething;

use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomething_Chain extends SchemaToSomethingCommonBase implements SchemaToSomethingInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToEvaluator\Partial\PartialSchemaToEvaluatorInterface[]
   */
  private $mappers;

  /**
   * @var string
   */
  private $resultInterface;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToEvaluator\Partial\PartialSchemaToEvaluatorInterface[] $mappers
   * @param string $resultInterface
   */
  public function __construct(array $mappers, $resultInterface) {
    parent::__construct($resultInterface);
    $this->mappers = $mappers;
    $this->resultInterface = $resultInterface;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema) {

    foreach ($this->mappers as $mapper) {
      if (NULL !== $candidate = $mapper->schemaGetEvaluator($schema)) {
        if ($candidate instanceof $this->resultInterface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }
}
