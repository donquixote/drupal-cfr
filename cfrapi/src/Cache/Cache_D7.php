<?php

namespace Drupal\cfrapi\Cache;

use Donquixote\Cf\Cache\CacheInterface;

class Cache_D7 implements CacheInterface {

  /**
   * @var string
   */
  private $bin;

  /**
   * @param string $bin
   */
  public function __construct($bin = 'cache') {
    $this->bin = $bin;
  }

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return mixed
   */
  public function getInto($key, &$value) {

    /** @noinspection PhpUndefinedFunctionInspection */
    if ($cached = cache_get($key, $this->bin)) {
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
    /** @noinspection PhpUndefinedFunctionInspection */
    cache_set($key, $value, $this->bin);
  }
}
