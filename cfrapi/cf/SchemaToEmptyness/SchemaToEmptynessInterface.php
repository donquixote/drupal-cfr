<?php

namespace Donquixote\Cf\SchemaToEmptyness;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToEmptynessInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  public function schemaGetEmptyness(CfSchemaInterface $schema);

}
