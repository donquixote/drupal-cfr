<?php

namespace Drupal\cfrrealm\TypeToDefmap;

use Drupal\cfrfamily\DefinitionMap\DefinitionMap_Buffer;
use Drupal\cfrfamily\DefinitionsById\DefinitionsById_Cache;
use Drupal\cfrrealm\DefinitionsById\DefinitionsById_FromType;
use Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap implements TypeToDefmapInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @var string|null
   */
  private $cachePrefix;

  /**
   * @param \Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
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
   * @return \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap($type) {
    $definitionsById = new DefinitionsById_FromType($this->typeToDefinitionsbyid, $type);
    if (NULL !== $this->cachePrefix) {
      $definitionsById = new DefinitionsById_Cache($definitionsById, $this->cachePrefix . ':' . $type);
    }
    return new DefinitionMap_Buffer($definitionsById);
  }
}
