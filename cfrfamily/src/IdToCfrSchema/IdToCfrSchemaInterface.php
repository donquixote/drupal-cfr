<?php

namespace Drupal\cfrfamily\IdToCfrSchema;

interface IdToCfrSchemaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetCfrSchema($id);

}
