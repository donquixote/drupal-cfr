<?php

namespace Drupal\cfrapi\Cache;

use Donquixote\Cf\Cache\CacheInterface;
use Drupal\Core\Cache\CacheBackendInterface;

class Cache_D8 implements CacheInterface {

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   */
  public function __construct(CacheBackendInterface $cache) {
    $this->cache = $cache;
  }

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return mixed
   */
  public function getInto($key, &$value) {

    if ($cached = $this->cache->get($key)) {
      $value = $cached->data;
      return TRUE;
    }
    else {
      $value = NULL;
      return FALSE;
    }
  }

  /**
   * @param string $key
   * @param mixed $value
   */
  public function set($key, $value) {
    $this->cache->set($key, $value);
  }

  /**
   * @param string $prefix
   */
  public function clear($prefix = '') {
    $this->cache->deleteAll();
  }
}
