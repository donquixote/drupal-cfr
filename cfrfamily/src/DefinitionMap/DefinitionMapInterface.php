<?php

namespace Drupal\cfrfamily\DefinitionMap;

use Drupal\cfrfamily\DefinitionsById\DefinitionsByIdInterface;
use Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface;

/**
 * Combination of two interfaces.
 */
interface DefinitionMapInterface extends DefinitionsByIdInterface, IdToDefinitionInterface {

}
