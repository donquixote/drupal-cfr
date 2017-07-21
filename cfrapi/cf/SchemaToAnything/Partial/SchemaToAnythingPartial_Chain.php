<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class SchemaToAnythingPartial_Chain implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private $partials;

  /**
   * @return self
   */
  public static function create() {
    $partials = LocalPackageUtil::collectSTAPartials();
    return new self($partials);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

    foreach ($this->partials as $mapper) {
      if (NULL !== $candidate = $mapper->schema($schema, $interface, $helper)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType($interface) {
    return TRUE;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSchemaClass($interface) {
    return TRUE;
  }
}
