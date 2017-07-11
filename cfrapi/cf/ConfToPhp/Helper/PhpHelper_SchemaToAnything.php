<?php

namespace Donquixote\Cf\ConfToPhp\Helper;

use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class PhpHelper_SchemaToAnything extends PhpHelperBase {

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
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf) {

    $evaluator = $this->schemaToAnything->schema(
      $schema,
      EvaluatorInterface::class);

    if (NULL === $evaluator) {
      return $this->unsupportedSchema();
    }

    if (!$evaluator instanceof EvaluatorInterface) {
      return $this->unsupportedSchema();
    }

    return $evaluator->confGetPhp($conf, $this);
  }
}
