<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Donquixote\Cf\Legacy\Emptyness\EmptynessInterface;
use Donquixote\Cf\Legacy\SchemaToEvaluator\SchemaToEvaluatorInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_PassthruBase;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class LegacyEvaluator_SequencePassthru extends LegacyEvaluator_SequenceBase {

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_PassthruBase $sequenceSchema
   * @param \Donquixote\Cf\Legacy\SchemaToEvaluator\SchemaToEvaluatorInterface $schemaToEvaluator
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface
   */
  public static function create(
    CfSchema_Sequence_PassthruBase $sequenceSchema,
    SchemaToEvaluatorInterface $schemaToEvaluator)
  {
    $itemEmptyness = $schemaToEvaluator->schemaGetEmptyness(
      $sequenceSchema->getItemSchema());

    return new self($itemEmptyness);
  }

  /**
   * @param \Donquixote\Cf\Legacy\Emptyness\EmptynessInterface $itemEmptyness
   */
  public function __construct(EmptynessInterface $itemEmptyness) {
    parent::__construct($itemEmptyness);
  }

  /**
   * @param mixed[] $values
   *
   * @return mixed
   */
  protected function itemValuesGetValue(array $values) {
    return $values;
  }

  /**
   * @param string[] $phpStatements
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $phpStatements, CfrCodegenHelperInterface $helper) {

    if ([] === $phpStatements) {
      return '[]';
    }

    $phpParts = [];
    foreach (array_values($phpStatements) as $delta => $deltaPhp) {
      $phpParts[] = ''
        . "\n// Sequence item #$delta"
        . "\n  $deltaPhp,";
    }

    $php = implode("\n", $phpParts);

    return "[$php\n]";
  }
}
