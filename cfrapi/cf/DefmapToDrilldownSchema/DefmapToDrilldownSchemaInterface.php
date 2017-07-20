<?php

namespace Donquixote\Cf\DefmapToDrilldownSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;

interface DefmapToDrilldownSchemaInterface {

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL);

}
