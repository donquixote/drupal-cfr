<?php

namespace Donquixote\Cf\TypeToDefmap;

use Donquixote\Cf\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\Cf\DefinitionsById\DefinitionsById_FromType;
use Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @param \Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   */
  public function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsbyid) {
    $this->typeToDefinitionsbyid = $typeToDefinitionsbyid;
  }

  /**
   * @param string $type
   *
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap($type) {
    $definitionsById = new DefinitionsById_FromType($this->typeToDefinitionsbyid, $type);
    return new DefinitionMap_Buffer($definitionsById);
  }
}
