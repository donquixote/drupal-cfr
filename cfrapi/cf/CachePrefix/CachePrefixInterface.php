<?php

namespace Donquixote\Cf\CachePrefix;

interface CachePrefixInterface {

  /**
   * @param string $key
   *
   * @return \Donquixote\Cf\CacheOffset\CacheOffsetInterface
   */
  public function getOffset($key);

  /**
   * @param string $prefix
   *
   * @return \Donquixote\Cf\CachePrefix\CachePrefixInterface
   */
  public function withAppendedPrefix($prefix);

  /**
   * Clears this section of the cache.
   */
  public function clear();

}
