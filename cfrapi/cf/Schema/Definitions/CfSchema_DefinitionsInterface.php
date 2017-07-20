<?php

namespace Donquixote\Cf\Schema\Definitions;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

interface CfSchema_DefinitionsInterface extends CfSchemaLocalInterface {

  /**
   * @return array[]
   */
  public function getDefinitions();

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext();

}
