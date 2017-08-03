<?php

namespace Donquixote\Cf\CachePrefix;

use Donquixote\Cf\Cache\CacheInterface;
use Donquixote\Cf\CacheOffset\CacheOffset;

class CachePrefix implements CachePrefixInterface {

  /**
   * @var \Donquixote\Cf\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $prefix;

  /**
   * @param \Donquixote\Cf\Cache\CacheInterface $cache
   * @param string $prefix
   */
  public function __construct(CacheInterface $cache, $prefix) {
    $this->cache = $cache;
    $this->prefix = $prefix;
  }

  /**
   * @param string $key
   *
   * @return \Donquixote\Cf\CacheOffset\CacheOffsetInterface
   */
  public function getOffset($key) {
    return new CacheOffset($this->cache, $this->prefix . $key);
  }

  /**
   * @param string $prefix
   *
   * @return \Donquixote\Cf\CachePrefix\CachePrefixInterface
   */
  public function withAppendedPrefix($prefix) {
    return new self($this->cache, $this->prefix . $prefix);
  }
}
