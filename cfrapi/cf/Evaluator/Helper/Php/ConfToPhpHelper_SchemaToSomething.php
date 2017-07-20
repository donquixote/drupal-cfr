<?php

namespace Donquixote\Cf\Evaluator\Helper\Php;

use Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface;

class ConfToPhpHelper_SchemaToSomething extends ConfToPhpHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface
   */
  private $schemaToEvaluator;

  /**
   * @param \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface $schemaToEvaluator
   */
  public function __construct(SchemaToSomethingInterface $schemaToEvaluator) {
    $schemaToEvaluator->requireResultType(EvaluatorPartialInterface::class);
    $this->schemaToEvaluator = $schemaToEvaluator;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf) {

    $evaluator = $this->schemaToEvaluator->schema($schema);

    if (NULL === $evaluator) {
      return $this->unsupportedSchema($schema);
    }

    if (!$evaluator instanceof EvaluatorPartialInterface) {
      $class = get_class($evaluator);
      throw new \RuntimeException("Misbehaving STS. Expected EvaluatorPartialInterface, found $class.");
    }

    return $evaluator->confGetPhp($conf, $this);
  }
}
