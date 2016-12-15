<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrfamily\IdConfToPhp\IdConfToPhpInterface;

interface IdConfToValueInterface extends IdConfToPhpInterface {

  /**
   * @param string|null $id
   * @param mixed $conf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $conf);

}
