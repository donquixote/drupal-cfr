<?php

namespace Donquixote\Cf\Schema\ValueProvider;

class CfSchema_ValueProvider_Null implements CfSchema_ValueProviderInterface {

  /**
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function getValue() {
    return NULL;
  }

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp() {
    return 'NULL';
  }
}
