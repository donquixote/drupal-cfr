<?php

namespace Donquixote\Cf\SchemaToSomething\Helper;

use Donquixote\Cf\SchemaToSomething\Partial\SchemaToSomethingPartialInterface;
use Donquixote\Cf\SchemaToSomething\SchemaToSomethingCommonBase;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomethingHelper extends SchemaToSomethingCommonBase implements SchemaToSomethingHelperInterface {

  /**
   * @var \Donquixote\Cf\SchemaToSomething\Partial\SchemaToSomethingPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Cf\SchemaToSomething\Partial\SchemaToSomethingPartialInterface $partial
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
