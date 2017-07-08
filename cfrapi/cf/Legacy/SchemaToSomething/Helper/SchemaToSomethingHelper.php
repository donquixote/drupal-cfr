<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething\Helper;

use Donquixote\Cf\Legacy\SchemaToSomething\Partial\SchemaToSomethingPartialInterface;
use Donquixote\Cf\Legacy\SchemaToSomething\SchemaToSomethingCommonBase;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomethingHelper extends SchemaToSomethingCommonBase implements SchemaToSomethingHelperInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToSomething\Partial\SchemaToSomethingPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToSomething\Partial\SchemaToSomethingPartialInterface $partial
   * @param string $resultInterface
   */
  public function __construct(SchemaToSomethingPartialInterface $partial, $resultInterface) {
    parent::__construct($resultInterface);
    $partial->requireResultType($resultInterface);
    $this->partial = $partial;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema) {
    return $this->partial->schema($schema, $this);
  }
}
