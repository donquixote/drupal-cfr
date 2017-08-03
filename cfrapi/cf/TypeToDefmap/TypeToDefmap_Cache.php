<?php

namespace Donquixote\Cf\TypeToDefmap;

use Donquixote\Cf\CachePrefix\CachePrefixInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\Cf\DefinitionsById\DefinitionsById_Cache;
use Donquixote\Cf\DefinitionsById\DefinitionsById_FromType;
use Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap_Cache implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @var \Donquixote\Cf\CachePrefix\CachePrefixInterface
   */
  private $cachePrefix;

  /**
   * @param \Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   * @param \Donquixote\Cf\CachePrefix\CachePrefixInterface $cachePrefix
   *   A prefix to prepend to the cache id, or NULL to have no cache.
   *   If specified, it should include the langcode.
   */
  public function __construct(
    TypeToDefinitionsbyidInterface $typeToDefinitionsbyid,
    CachePrefixInterface $cachePrefix = NULL
  ) {
    $this->typeToDefinitionsbyid = $typeToDefinitionsbyid;
    $this->cachePrefix = $cachePrefix;
  }

  /**
   * @param string $type
   *
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap($type) {
    $definitionsById = new DefinitionsById_FromType($this->typeToDefinitionsbyid, $type);
    if (NULL !== $this->cachePrefix) {
      $definitionsById = new DefinitionsById_Cache(
        $definitionsById,
        $this->cachePrefix->getOffset($type));
    }
    return new DefinitionMap_Buffer($definitionsById);
  }
}
