<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything\Helper;

use Donquixote\Cf\Legacy\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnythingHelper implements SchemaToAnythingHelperInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial
   */
  public function __construct(SchemaToAnythingPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {
    return $this->partial->schema($schema, $interface, $this);
  }
}
