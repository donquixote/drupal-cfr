<?php

namespace Donquixote\Cf\V2V\Id;

class V2V_Id_Trivial implements V2V_IdInterface {

  /**
   * @param string|int $id
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idGetValue($id) {
    return $id;
  }

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id) {
    return var_export($id, TRUE);
  }
}
