<?php

namespace Drupal\cfrfamily\IdMap;

interface IdMapInterface {

  /**
   * @return string[]
   */
  public function getIds();

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id);

}
