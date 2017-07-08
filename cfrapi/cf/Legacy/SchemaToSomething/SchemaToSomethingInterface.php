<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToSomethingInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema);

  /**
   * @param string $expectedResultInterface
   *
   * @throws \Exception
   */
  public function requireResultType($expectedResultInterface);

}
