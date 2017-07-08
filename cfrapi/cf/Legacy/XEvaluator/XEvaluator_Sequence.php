<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;

class XEvaluator_Sequence extends XEvaluator_SequenceBase {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $sequenceSchema;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   */
  public function __construct(CfSchema_SequenceInterface $sequenceSchema) {
    parent::__construct($sequenceSchema->getItemSchema());
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
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $phpStatements, PhpHelperInterface $helper) {

    return $this->sequenceSchema->itemsPhpGetPhp(
      $phpStatements,
      $helper->getCodegenHelper());
  }
}
