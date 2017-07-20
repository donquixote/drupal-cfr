<?php

namespace Donquixote\Cf\DefinitionMap;

use Donquixote\Cf\DefinitionsById\DefinitionsByIdInterface;
use Donquixote\Cf\IdToDefinition\IdToDefinitionInterface;

/**
 * Combination of two interfaces.
 */
interface DefinitionMapInterface extends DefinitionsByIdInterface, IdToDefinitionInterface {

}
