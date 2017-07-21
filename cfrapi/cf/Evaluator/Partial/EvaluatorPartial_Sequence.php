<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface;
use Donquixote\Cf\V2V\Sequence\V2V_Sequence_Trivial;
use Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface;

class EvaluatorPartial_Sequence implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $itemSchema;

  /**
   * @var \Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   *
   * @return self
   */
  public static function createFromSequenceSchema(CfSchema_SequenceInterface $schema) {
    return new self($schema->getItemSchema(), new V2V_Sequence_Trivial());
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface $schema
   *
   * @return self
   */
  public static function createFromSequenceValSchema(CfSchema_SequenceValInterface $schema) {
    return new self($schema->getDecorated()->getItemSchema(), $schema->getV2V());
  }



  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $itemSchema
   * @param \Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  protected function __construct(CfSchemaInterface $itemSchema, V2V_SequenceInterface $v2v) {
    $this->itemSchema = $itemSchema;
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

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

      $values[] = $helper->schemaConfGetValue($this->itemSchema, $itemConf);
    }

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->incompatibleConfiguration($conf, "Sequence array keys must be non-negative integers.");
      }

      $phpStatements[] = $helper->schemaConfGetPhp($this->itemSchema, $itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
