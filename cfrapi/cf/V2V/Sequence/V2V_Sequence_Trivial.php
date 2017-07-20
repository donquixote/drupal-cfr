<?php

namespace Donquixote\Cf\V2V\Sequence;

use Donquixote\Cf\Util\PhpUtil;

class V2V_Sequence_Trivial implements V2V_SequenceInterface {

  /**
   * @param mixed[] $values
   *   Format: $[] = $itemValue
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
