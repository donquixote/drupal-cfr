<?php

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;

interface CfSchema_GroupInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas();

  /**
   * @return string[]
   */
  public function getLabels();

}
