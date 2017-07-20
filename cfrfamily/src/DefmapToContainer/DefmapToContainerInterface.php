<?php

namespace Drupal\cfrfamily\DefmapToContainer;

use Drupal\cfrapi\Context\CfrContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;

interface DefmapToContainerInterface {

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface
   */
  public function defmapGetContainer(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL);

}
