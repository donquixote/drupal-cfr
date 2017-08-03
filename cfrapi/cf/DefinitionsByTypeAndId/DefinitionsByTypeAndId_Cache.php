<?php

namespace Donquixote\Cf\DefinitionsByTypeAndId;

use Donquixote\Cf\CacheOffset\CacheOffsetInterface;

class DefinitionsByTypeAndId_Cache implements DefinitionsByTypeAndIdInterface {

  /**
   * @var \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\CacheOffset\CacheOffsetInterface
   */
  private $cacheOffset;

  /**
   * @param \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $decorated
   * @param \Donquixote\Cf\CacheOffset\CacheOffsetInterface $cacheOffset
   */
  public function __construct(
    DefinitionsByTypeAndIdInterface $decorated,
    CacheOffsetInterface $cacheOffset
  ) {
    $this->decorated = $decorated;
    $this->cacheOffset = $cacheOffset;
  }

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId() {

    if ($this->cacheOffset->getInto($value)) {
      return $value;
    }

    $definitions = $this->decorated->getDefinitionsByTypeAndId();

    $this->cacheOffset->set($definitions);

    return $definitions;
  }
}
