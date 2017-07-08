<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything;

use Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnything_Decorator implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToAnything\SchemaToAnythingInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return self
   */
  public static function createFromHelper(SchemaToAnythingHelperInterface $helper) {
    return new self($helper);
  }

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\SchemaToAnythingInterface $decorated
   */
  public function __construct(SchemaToAnythingInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {
    return $this->decorated->schema($schema, $interface);
  }
}
