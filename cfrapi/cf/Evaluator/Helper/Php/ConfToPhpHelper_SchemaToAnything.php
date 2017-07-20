<?php

namespace Donquixote\Cf\Evaluator\Helper\Php;

use Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;

class ConfToPhpHelper_SchemaToAnything extends ConfToPhpHelperBase {

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
      EvaluatorPartialInterface::class);

    if (NULL === $evaluator) {
      # kdpm($schema, __METHOD__);
      return PhpUtil::unableToSTA($schema, EvaluatorPartialInterface::class);
    }

    if (!$evaluator instanceof EvaluatorPartialInterface) {

      return PhpUtil::misbehavingSTA(
        $schema,
        EvaluatorPartialInterface::class,
        $evaluator);
    }

    return $evaluator->confGetPhp($conf, $this);
  }
}
