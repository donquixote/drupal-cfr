<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_PassthruBase;

class XEvaluator_SequencePassthru extends XEvaluator_SequenceBase {

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_PassthruBase $schema
   */
  public function __construct(CfSchema_Sequence_PassthruBase $schema) {
    parent::__construct($schema->getItemSchema());
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
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $phpStatements, PhpHelperInterface $helper) {

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
