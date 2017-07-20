<?php

namespace Drupal\cfrrealm\TypeToDefmap;

use Donquixote\Cf\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\Cf\DefinitionsById\DefinitionsById_FromType;
use Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;
use Drupal\cfrfamily\DefinitionsById\DefinitionsById_Cache;

class TypeToDefmap_Cache implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @var string|null
   */
  private $cachePrefix;

  /**
   * @param \Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   * @param string|null $cachePrefix
   *   A prefix to prepend to the cache id, or NULL to have no cache.
   *   If specified, it should include the langcode.
   */
  public function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsbyid, $cachePrefix) {
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
      $definitionsById = new DefinitionsById_Cache($definitionsById, $this->cachePrefix . ':' . $type);
    }
    return new DefinitionMap_Buffer($definitionsById);
  }
}
