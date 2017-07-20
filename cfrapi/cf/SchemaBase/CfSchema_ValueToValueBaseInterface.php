<?php

namespace Donquixote\Cf\SchemaBase;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

/**
 * Base interface for all schema types where the configuration form and summary
 * is the same as for the decorated schema.
 */
interface CfSchema_ValueToValueBaseInterface extends CfSchemaLocalInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

}
