<?php

namespace Donquixote\Cf\Schema\Optional;

class CfSchema_Optional extends CfSchema_OptionalBase {

  /**
   * @var string|null
   */
  private $emptySummary;

  /**
   * @var mixed
   */
  private $emptyValue = NULL;

  /**
   * @var string
   */
  private $emptyPhp = 'NULL';

  /**
   * @param string $emptySummary
   *
   * @return static
   */
  public function withEmptySummary($emptySummary) {
    $clone = clone $this;
    $clone->emptySummary = $emptySummary;
    return $clone;
  }

  /**
   * @param mixed $emptyValue
   * @param string|null $emptyPhp
   *
   * @return \Donquixote\Cf\Schema\Optional\CfSchema_Optional
   */
  public function withEmptyValue($emptyValue, $emptyPhp = NULL) {

    $clone = clone $this;
    $clone->emptyValue = $emptyValue;
    $clone->emptyPhp = (NULL !== $emptyPhp)
      ? $emptyPhp
      : var_export($emptyValue, TRUE);

    return $clone;
  }

  /**
   * @return null|string
   */
  public function getEmptySummary() {
    return $this->emptySummary;
  }

  /**
   * @return mixed
   *   Typically NULL.
   */
  public function getEmptyValue() {
    return $this->emptyValue;
  }

  /**
   * @return string
   *   Typically 'NULL'.
   */
  public function getEmptyPhp() {
    return $this->emptyPhp;
  }
}
