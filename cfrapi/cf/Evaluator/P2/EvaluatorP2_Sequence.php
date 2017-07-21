<?php

namespace Donquixote\Cf\Evaluator\P2;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\V2V\Sequence\V2V_Sequence_Trivial;
use Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface;

class EvaluatorP2_Sequence implements EvaluatorP2Interface {

  /**
   * @var \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  private $itemEvaluator;

  /**
   * @var \Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function createFromSequenceSchema(CfSchema_SequenceInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::create($schema, new V2V_Sequence_Trivial(), $schemaToAnything);
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function createFromSequenceValSchema(CfSchema_SequenceValInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  private static function create(CfSchema_SequenceInterface $schema, V2V_SequenceInterface $v2v, SchemaToAnythingInterface $schemaToAnything) {

    $itemEvaluator = StaUtil::evaluatorP2(
      $schema->getItemSchema(),
      $schemaToAnything);

    if (NULL === $itemEvaluator) {
      return NULL;
    }

    return new self($itemEvaluator, $v2v);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $itemEvaluator
   * @param \Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  protected function __construct(EvaluatorP2Interface $itemEvaluator, V2V_SequenceInterface $v2v) {
    $this->itemEvaluator = $itemEvaluator;
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public function confGetValue($conf) {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      throw new EvaluatorException_IncompatibleConfiguration('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        $deltaExport = var_export($delta, TRUE);
        // Fail on non-numeric and negative keys.
        throw new EvaluatorException_IncompatibleConfiguration(''
          . "Deltas must be non-negative integers."
          . "\n" . "Found $deltaExport instead.");
      }

      $values[] = $this->itemEvaluator->confGetValue($itemConf);
    }

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return PhpUtil::incompatibleConfiguration("Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return PhpUtil::incompatibleConfiguration("Sequence array keys must be non-negative integers.");
      }

      $phpStatements[] = $this->itemEvaluator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
