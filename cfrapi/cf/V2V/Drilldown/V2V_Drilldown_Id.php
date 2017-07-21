<?php

namespace Donquixote\Cf\V2V\Drilldown;

class V2V_Drilldown_Id implements V2V_DrilldownInterface {

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
