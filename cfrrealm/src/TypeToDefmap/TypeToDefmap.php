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
  private $cacheSuffix;

  /**
   * @param \Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   * @param string|null $cacheSuffix
   *   The langcode to append to the cache id, or NULL to have no cache.
   */
  function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsbyid, $cacheSuffix) {
    $this->typeToDefinitionsbyid = $typeToDefinitionsbyid;
    $this->cacheSuffix = $cacheSuffix;
  }

  /**
   * @param string $type
   *
   * @return \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  function typeGetDefmap($type) {
    $definitionsById = new DefinitionsById_FromType($this->typeToDefinitionsbyid, $type);
    if (NULL !== $this->cacheSuffix) {
      $definitionsById = new DefinitionsById_Cache($definitionsById, 'cfrrealm:definitions:' . $type . ':' . $this->cacheSuffix);
    }
    return new DefinitionMap_Buffer($definitionsById);
  }
}
