<?php

namespace Donquixote\Cf\CacheOffset;

use Donquixote\Cf\Cache\CacheInterface;

class CacheOffset implements CacheOffsetInterface {

  /**
   * @var \Donquixote\Cf\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $key;

  /**
   * @param \Donquixote\Cf\Cache\CacheInterface $cache
   * @param string $key
   */
  public function __construct(CacheInterface $cache, $key) {
    $this->cache = $cache;
    $this->key = $key;
  }

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function getInto(&$value) {
    return $this->cache->getInto($this->key, $value);
  }

  /**
   * @param mixed $value
   */
  public function set($value) {
    return $this->cache->set($this->key, $value);
  }
}
