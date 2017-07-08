<?php

namespace Donquixote\Cf\Legacy\SchemaToAnything\Partial;

use Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToAnythingPartial_Chain implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private $mappers;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $mappers
   */
  public function __construct(array $mappers) {
    $this->mappers = $mappers;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

    foreach ($this->mappers as $mapper) {
      if (NULL !== $candidate = $mapper->schema($schema, $interface, $helper)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

}
