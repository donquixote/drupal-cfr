<?php

namespace Donquixote\Cf\V2V\Group;

use Donquixote\Cf\Util\PhpUtil;

class V2V_Group_Trivial implements V2V_GroupInterface {

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {
    return $values;
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {
    return PhpUtil::phpArray($itemsPhp);
  }
}
