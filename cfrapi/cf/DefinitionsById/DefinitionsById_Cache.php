<?php

namespace Donquixote\Cf\DefinitionsById;

use Donquixote\Cf\CacheOffset\CacheOffsetInterface;

class DefinitionsById_Cache implements DefinitionsByIdInterface {

  /**
   * @var \Donquixote\Cf\DefinitionsById\DefinitionsByIdInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\CacheOffset\CacheOffsetInterface
   */
  private $cacheOffset;

  /**
   * @param \Donquixote\Cf\DefinitionsById\DefinitionsByIdInterface $decorated
   * @param \Donquixote\Cf\CacheOffset\CacheOffsetInterface $cacheOffset
   */
  public function __construct(
    DefinitionsByIdInterface $decorated,
    CacheOffsetInterface $cacheOffset
  ) {
    $this->decorated = $decorated;
    $this->cacheOffset = $cacheOffset;
  }

  /**
   * @param string $id
   *
   * @return array|null
   */
  public function idGetDefinition($id) {
    $definitions = $this->getDefinitionsById();
    return isset($definitions[$id])
      ? $definitions[$id]
      : NULL;
  }

  /**
   * @return array[]
   */
  public function getDefinitionsById() {

    if ($this->cacheOffset->getInto($value)) {
      return $value;
    }

    $definitions = $this->decorated->getDefinitionsById();

    $this->cacheOffset->set($definitions);

    return $definitions;
  }
}
