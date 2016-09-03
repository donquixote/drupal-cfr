<?php

namespace Drupal\cfrfamily\IdConfToValue;

interface IdConfToValueInterface {

  /**
   * @param string|null $id
   * @param mixed $conf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $conf);

}
