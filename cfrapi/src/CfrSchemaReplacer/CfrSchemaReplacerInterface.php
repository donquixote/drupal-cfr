<?php

namespace Drupal\cfrapi\CfrSchemaReplacer;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface CfrSchemaReplacerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   *   A transformed schema, or NULL if no replacement can be found.
   */
  public function schemaGetReplacement(CfSchemaInterface $cfrSchema);

}
