<?php

namespace Donquixote\Cf\Legacy\SchemaToEmptyness;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToEmptynessInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   */
  public function schemaGetEmptyness(CfSchemaInterface $schema);

}
