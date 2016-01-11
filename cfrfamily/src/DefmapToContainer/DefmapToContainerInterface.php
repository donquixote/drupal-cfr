<?php

namespace Drupal\cfrfamily\DefmapToContainer;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

interface DefmapToContainerInterface {

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface
   */
  function defmapGetContainer(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL);

}
