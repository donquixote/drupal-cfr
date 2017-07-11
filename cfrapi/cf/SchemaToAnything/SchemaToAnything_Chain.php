<?php

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class SchemaToAnything_Chain implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface[]
   */
  private $mappers;

  /**
   * @return self
   */
  public static function create() {
    $mappers = LocalPackageUtil::collectSTAMappers();
    return new self($mappers);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface[] $mappers
   */
  public function __construct(array $mappers) {
    $this->mappers = $mappers;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {

    foreach ($this->mappers as $mapper) {
      if (NULL !== $candidate = $mapper->schema($schema, $interface)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }
}
