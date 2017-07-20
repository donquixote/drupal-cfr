<?php
namespace Drupal\cfrfamily\DefmapToCfrFamily;

use Drupal\cfrapi\Context\CfrContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;

interface DefmapToCfrFamilyInterface {

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  public function defmapGetCfrFamily(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL);
}
