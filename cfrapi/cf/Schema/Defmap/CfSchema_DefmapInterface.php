<?php

namespace Donquixote\Cf\Schema\Defmap;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

interface CfSchema_DefmapInterface extends CfSchemaLocalInterface {

  /**
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  public function getDefinitionMap();

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext();

}
