<?php

namespace Donquixote\Cf\V2V\Sequence;

interface V2V_SequenceInterface {

  /**
   * @param mixed[] $values
   *   Format: $[] = $itemValue
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values);

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp);

}
