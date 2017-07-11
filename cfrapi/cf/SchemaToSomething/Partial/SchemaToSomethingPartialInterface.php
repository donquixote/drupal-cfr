<?php

namespace Donquixote\Cf\SchemaToSomething\Partial;

use Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToSomethingPartialInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
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
