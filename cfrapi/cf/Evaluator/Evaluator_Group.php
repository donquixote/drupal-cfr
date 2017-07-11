<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;

class Evaluator_Group extends Evaluator_GroupBase {

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
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $itemsPhp, PhpHelperInterface $helper) {
    return $this->groupSchema->itemsPhpGetPhp($itemsPhp);
  }
}
