<?php

namespace Donquixote\Cf\Schema\SequenceVal;

use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\Cf\V2V\Sequence\V2V_SequenceInterface;

interface CfSchema_SequenceValInterface extends V2V_SequenceInterface, CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  public function getDecorated();

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
