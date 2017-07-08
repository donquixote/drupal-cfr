<?php

namespace Drupal\cfrfamily\DefmapToDrilldownSchema;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

interface DefmapToDrilldownSchemaInterface {

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL);

}
