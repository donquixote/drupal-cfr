<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

class CfSchema_DrilldownVal_Id extends CfSchema_DrilldownValBase {

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($id, $value) {
    return $id;
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php) {
    return var_export($id, TRUE);
  }
}
