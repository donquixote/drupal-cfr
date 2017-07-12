<?php

namespace Donquixote\Cf\Schema\Definition;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface CfSchema_DefinitionInterface extends CfSchemaInterface {

  /**
   * @return array
   */
  public function getDefinition();

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext();

}
