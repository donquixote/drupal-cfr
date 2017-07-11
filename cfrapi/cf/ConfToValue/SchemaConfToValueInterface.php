<?php

namespace Donquixote\Cf\ConfToValue;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaConfToValueInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf);

}
