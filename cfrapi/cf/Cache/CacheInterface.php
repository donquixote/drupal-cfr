<?php

namespace Donquixote\Cf\Cache;

interface CacheInterface {

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return bool
   */
  public function getInto($key, &$value);

  /**
   * @param string $key
   * @param mixed $value
   */
  public function set($key, $value);

  /**
   * @param string $prefix
   */
  public function clear($prefix = '');

}
