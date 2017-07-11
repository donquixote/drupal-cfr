<?php

namespace Donquixote\Cf\ConfToPhp\Helper;

use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface;

class PhpHelper_SchemaToSomething extends PhpHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface
   */
  private $schemaToEvaluator;

  /**
   * @param \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface $schemaToEvaluator
   */
  public function __construct(SchemaToSomethingInterface $schemaToEvaluator) {
    $schemaToEvaluator->requireResultType(EvaluatorInterface::class);
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
      return $this->unsupportedSchema();
    }

    if (!$evaluator instanceof EvaluatorInterface) {
      return $this->unsupportedSchema();
    }

    return $evaluator->confGetPhp($conf, $this);
  }
}
