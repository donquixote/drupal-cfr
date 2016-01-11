<?php

namespace Drupal\cfrfamily\DefmapToCfrmap;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

interface DefmapToCfrmapInterface {

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface
   */
  function defmapGetCfrmap(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL);

}
