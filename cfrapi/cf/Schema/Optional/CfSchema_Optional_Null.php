<?php

namespace Donquixote\Cf\Schema\Optional;

class CfSchema_Optional_Null extends CfSchema_OptionalBase {

  /**
   * @return string|null
   *
   * @todo Does this need a helper?
   */
  public function getEmptySummary() {
    return NULL;
  }

  /**
   * @return mixed
   *   Typically NULL.
   */
  final public function getEmptyValue() {
    return NULL;
  }

  /**
   * @return string
   *   Typically 'NULL'.
   */
  final public function getEmptyPhp() {
    return 'NULL';
  }
}
