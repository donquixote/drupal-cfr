<?php

namespace Drupal\cfrfamily\IdConfToValue;

interface IdConfToValueInterface {

  /**
   * @param string|null $id
   * @param mixed $conf
   *
   * @return mixed
   */
  function idConfGetValue($id, $conf);

}
