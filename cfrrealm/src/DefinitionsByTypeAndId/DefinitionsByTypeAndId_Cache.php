<?php

namespace Drupal\cfrrealm\DefinitionsByTypeAndId;



class DefinitionsByTypeAndId_Cache implements DefinitionsByTypeAndIdInterface {

  const CACHE_BIN = 'cache';

  /**
   * @var \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $decorated;

  /**
   * @var string
   */
  private $cid;

  /**
   * DefinitionsByTypeAndIdBuffer constructor.
   *
   * @param \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $decorated
   * @param string $cid
   */
  public function __construct(DefinitionsByTypeAndIdInterface $decorated, $cid) {
    $this->decorated = $decorated;
    $this->cid = $cid;
  }

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId() {
    if ($cache = cache_get($this->cid, self::CACHE_BIN)) {
      # dpm($cache->data);
      return $cache->data;
    }
    $definitions = $this->decorated->getDefinitionsByTypeAndId();
    cache_set($this->cid, $definitions, self::CACHE_BIN);
    return $definitions;
  }
}
