<?php

namespace Donquixote\Cf\ConfToValue\Helper;

use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class ConfToValueHelper_SchemaToAnything extends ConfToValueHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(SchemaToAnythingInterface $schemaToAnything) {
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {

    $evaluator = $this->schemaToAnything->schema(
      $schema,
      EvaluatorInterface::class);

    if (NULL === $evaluator) {
      return NULL;
    }

    if (!$evaluator instanceof EvaluatorInterface) {
      return NULL;
    }

    return $evaluator->confGetValue($conf, $this);
  }
}
