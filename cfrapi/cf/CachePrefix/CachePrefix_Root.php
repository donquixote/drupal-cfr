<?php

namespace Donquixote\Cf\CachePrefix;

use Donquixote\Cf\Cache\CacheInterface;
use Donquixote\Cf\CacheOffset\CacheOffset;

class CachePrefix_Root implements CachePrefixInterface {

  /**
   * @var \Donquixote\Cf\Cache\CacheInterface
   */
  private $cache;

  /**
   * @param \Donquixote\Cf\Cache\CacheInterface $cache
   */
  public function __construct(CacheInterface $cache) {
    $this->cache = $cache;
  }

  /**
   * @param string $key
   *
   * @return \Donquixote\Cf\CacheOffset\CacheOffsetInterface
   */
  public function getOffset($key) {
    return new CacheOffset($this->cache, $key);
  }

  /**
   * @param string $prefix
   *
   * @return \Donquixote\Cf\CachePrefix\CachePrefixInterface
   */
  public function withAppendedPrefix($prefix) {
    return new CachePrefix($this->cache, $prefix);
  }

  /**
   * Clears this section of the cache.
   */
  public function clear() {
    $this->cache->clear();
  }
}
