<?php

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToAnythingInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface);

}
