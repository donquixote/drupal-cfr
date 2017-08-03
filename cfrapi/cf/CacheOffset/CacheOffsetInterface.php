<?php

namespace Donquixote\Cf\CacheOffset;

interface CacheOffsetInterface {

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function getInto(&$value);

  /**
   * @param mixed $value
   */
  public function set($value);

}
