<?php

namespace Donquixote\Cf\SchemaToAnything\Helper;

use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnythingHelper implements SchemaToAnythingHelperInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial
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
