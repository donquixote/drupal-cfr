<?php

namespace Donquixote\Cf\ATA\Partial;

use Donquixote\Cf\ATA\ATAInterface;

abstract class ATAPartialBase implements ATAPartialInterface {

  /**
   * @var null|string
   */
  private $sourceType;

  /**
   * @var null|string
   */
  private $resultType;

  /**
   * @var int
   */
  private $specifity = 0;

  /**
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  protected function __construct($sourceType = NULL, $resultType = NULL) {
    $this->sourceType = $sourceType;
    $this->resultType = $resultType;
  }

  /**
   * @param int $specifity
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialBase
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
   * @param string $sourceType
   *
   * @return static
   */
  public function withSourceType($sourceType) {

    if ($sourceType === $this->sourceType) {
      return $this;
    }

    $clone = clone $this;
    $clone->sourceType = $sourceType;
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
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Cf\ATA\ATAInterface $helper
   *
   * @return null|object An instance of $interface, or NULL.
   * An instance of $interface, or NULL.
   */
  final public function cast(
    $source,
    $interface,
    ATAInterface $helper
  ) {

    if (NULL !== $this->sourceType && !$source instanceof $this->sourceType) {
      return NULL;
    }

    $candidate = $this->doCast($source, $interface, $helper);

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
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Cf\ATA\ATAInterface $helper
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  abstract protected function doCast(
    $source,
    $interface,
    ATAInterface $helper
  );

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass) {
    return NULL === $this->sourceType
      || is_a($sourceClass, $this->sourceType, TRUE);
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
