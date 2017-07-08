<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Donquixote\Cf\Legacy\Emptyness\EmptynessInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_PassthruBase;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Legacy\SchemaToEvaluator\SchemaToEvaluatorInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class LegacyEvaluator_Sequence extends LegacyEvaluator_SequenceBase {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $sequenceSchema;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   * @param \Donquixote\Cf\Legacy\SchemaToEvaluator\SchemaToEvaluatorInterface $schemaToEvaluator
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface
   */
  public static function create(
    CfSchema_SequenceInterface $sequenceSchema,
    SchemaToEvaluatorInterface $schemaToEvaluator)
  {
    $itemEmptyness = $schemaToEvaluator->schemaGetEmptyness(
      $sequenceSchema->getItemSchema());

    if ($sequenceSchema instanceof CfSchema_Sequence_PassthruBase) {
      return new LegacyEvaluator_SequencePassthru($itemEmptyness);
    }

    return new self($itemEmptyness, $sequenceSchema);
  }

  /**
   * @param \Donquixote\Cf\Legacy\Emptyness\EmptynessInterface $itemEmptyness
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   */
  public function __construct(
    EmptynessInterface $itemEmptyness,
    CfSchema_SequenceInterface $sequenceSchema)
  {
    parent::__construct($itemEmptyness);
    $this->sequenceSchema = $sequenceSchema;
  }

  /**
   * @param mixed[] $values
   *
   * @return mixed
   */
  protected function itemValuesGetValue(array $values) {
    return $this->sequenceSchema->valuesGetValue($values);
  }

  /**
   * @param string[] $phpStatements
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $phpStatements, CfrCodegenHelperInterface $helper) {

    return $this->sequenceSchema->itemsPhpGetPhp(
      $phpStatements,
      $helper);
  }
}
