<?php

namespace Donquixote\Cf\SchemaReplacer;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaReplacerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   *   A transformed schema, or NULL if no replacement can be found.
   */
  public function schemaGetReplacement(CfSchemaInterface $schema);

}
