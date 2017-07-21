<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;

abstract class SchemaToAnythingPartialBase implements SchemaToAnythingPartialInterface {

  /**
   * @var null|string
   */
  private $schemaType;

  /**
   * @var null|string
   */
  private $resultType;

  /**
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  protected function __construct($schemaType = NULL, $resultType = NULL) {
    $this->schemaType = $schemaType;
    $this->resultType = $resultType;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  final public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

    if (NULL !== $this->schemaType && !$schema instanceof $this->schemaType) {
      return NULL;
    }

    $candidate = $this->schemaDoGetObject($schema, $interface, $helper);

    if (NULL === $candidate) {
      return NULL;
    }

    if (!$candidate instanceof $interface) {
      kdpm($candidate, "Expected $interface, found sth else.");
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  abstract protected function schemaDoGetObject(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  );

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass($schemaClass) {
    return NULL === $this->schemaType
      || is_a($schemaClass, $this->schemaType, TRUE);
  }

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface) {
    return NULL === $this->resultType
      || is_a($this->resultType, $resultInterface, TRUE);
  }
}
