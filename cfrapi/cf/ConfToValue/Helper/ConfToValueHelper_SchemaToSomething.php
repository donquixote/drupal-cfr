<?php

namespace Donquixote\Cf\ConfToValue\Helper;

use Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class ConfToValueHelper_SchemaToSomething extends ConfToValueHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface
   */
  private $schemaToEvaluator;

  /**
   * @param \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface $schemaToEvaluator
   */
  public function __construct(SchemaToSomethingInterface $schemaToEvaluator) {

    $schemaToEvaluator->requireResultType(
      EvaluatorInterface::class);

    $this->schemaToEvaluator = $schemaToEvaluator;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {

    $evaluator = $this->schemaToEvaluator->schema($schema);

    if (NULL === $evaluator) {
      return NULL;
    }

    if (!$evaluator instanceof EvaluatorInterface) {
      return NULL;
    }

    return $evaluator->confGetValue($conf, $this);
  }
}
