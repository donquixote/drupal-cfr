<?php

namespace Donquixote\Cf\SchemaBase\Options;

use Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractIdInterface;

/**
 * This is a base interface, which by itself does NOT extend CfSchemaInterface.
 */
interface CfSchemaBase_AbstractOptionsInterface extends CfSchemaBase_AbstractIdInterface {

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions();

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id);

}
