<?php
namespace Drupal\cfrfamily\DefmapToCfrFamily;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

interface DefmapToCfrFamilyInterface {

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  public function defmapGetCfrFamily(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL);
}
