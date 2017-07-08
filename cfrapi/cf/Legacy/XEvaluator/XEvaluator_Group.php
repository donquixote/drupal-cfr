<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;

class XEvaluator_Group extends XEvaluator_GroupBase {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $groupSchema;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   */
  public function __construct(CfSchema_GroupInterface $groupSchema) {
    parent::__construct($groupSchema);
    $this->groupSchema = $groupSchema;
  }

  /**
   * @param mixed[] $itemValues
   *
   * @return mixed
   */
  protected function itemValuesGetValue(array $itemValues) {
    return $this->groupSchema->valuesGetValue($itemValues);
  }

  /**
   * @param string[] $itemsPhp
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $itemsPhp, PhpHelperInterface $helper) {
    return $this->groupSchema->itemsPhpGetPhp($itemsPhp, $helper->getCodegenHelper());
  }
}
