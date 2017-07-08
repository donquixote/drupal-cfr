<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething\Partial;

use Donquixote\Cf\Legacy\SchemaToSomething\Helper\SchemaToSomethingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToSomethingPartialInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\Legacy\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema, SchemaToSomethingHelperInterface $helper);

  /**
   * @param string $expectedResultInterface
   *
   * @throws \Exception
   */
  public function requireResultType($expectedResultInterface);

}
