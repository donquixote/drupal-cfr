<?php

namespace Donquixote\Cf\IdToSchema;

interface IdToSchemaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id);

}
