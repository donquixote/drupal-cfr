<?php

namespace Drupal\cfrrealm\DefinitionsById;

use Drupal\cfrfamily\DefinitionsById\DefinitionsByIdInterface;
use Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class DefinitionsById_FromType implements DefinitionsByIdInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsById;

  /**
   * @var string
   */
  private $type;

  /**
   * WickedDefinitionsByIdDiscovery constructor.
   *
   * @param \Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsById
   * @param string $type
   */
  public function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsById, $type) {
    $this->typeToDefinitionsById = $typeToDefinitionsById;
    $this->type = $type;
  }

  /**
   * @return array[]
   *   Array of all plugin definitions for this plugin type.
   */
  public function getDefinitionsById() {
    return $this->typeToDefinitionsById->typeGetDefinitionsbyid($this->type);
  }

}
