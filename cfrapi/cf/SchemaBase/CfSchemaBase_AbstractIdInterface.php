<?php

namespace Donquixote\Cf\SchemaBase;

interface CfSchemaBase_AbstractIdInterface {

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id);

}
