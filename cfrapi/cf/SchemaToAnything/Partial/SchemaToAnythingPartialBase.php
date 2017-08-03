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
   * @var int
   */
  private $specifity = 0;

  /**
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  protected function __construct($schemaType = NULL, $resultType = NULL) {
    $this->schemaType = $schemaType;
    $this->resultType = $resultType;
  }

  /**
   * @param int $specifity
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialBase
   */
  public function withSpecifity($specifity) {

    if ($specifity === $this->specifity) {
      return $this;
    }

    $clone = clone $this;
    $clone->specifity = $specifity;
    return $clone;
  }

  /**
   * @param string $schemaType
   *
   * @return static
   */
  public function withSchemaType($schemaType) {

    if ($schemaType === $this->schemaType) {
      return $this;
    }

    $clone = clone $this;
    $clone->schemaType = $schemaType;
    return $clone;
  }

  /**
   * @param string $resultType
   *
   * @return static
   */
  public function withResultType($resultType) {

    if ($resultType === $this->resultType) {
      return $this;
    }

    $clone = clone $this;
    $clone->resultType = $resultType;
    return $clone;
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
      # kdpm($candidate, "Expected $interface, found sth else.");
      # kdpm($this, '$this');
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

  /**
   * @return int
   */
  public function getSpecifity() {
    return $this->specifity;
  }
}
