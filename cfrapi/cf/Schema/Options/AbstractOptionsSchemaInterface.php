<?php

namespace Donquixote\Cf\Schema\Options;

/**
 * This is a base interface, which by itself does NOT extend CfrSchemaInterface.
 */
interface AbstractOptionsSchemaInterface {

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

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id);

}
