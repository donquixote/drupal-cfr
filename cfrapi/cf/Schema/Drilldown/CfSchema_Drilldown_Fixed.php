<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Schema\CfSchemaInterface;

class CfSchema_Drilldown_Fixed implements CfSchema_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function create(array $schemas = [], array $labels = []) {
    return new self($schemas, $labels);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   */
  public function __construct(array $schemas = [], array $labels = []) {
    $this->schemas = $schemas;
    $this->labels = $labels;
  }

  /**
   * @param string $id
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|null $label
   *
   * @return static
   */
  public function withOption($id, CfSchemaInterface $schema, $label = NULL) {
    $clone = clone $this;
    $clone->schemas[$id] = $schema;
    $clone->labels[$id] = NULL !== $label
      ? $label
      : $id;
    return $clone;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return isset($this->schemas[$id]);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {

    $labels = [];
    foreach ($this->schemas as $id => $_) {
      $labels[$id] = isset($this->labels[$id])
        ? $this->labels[$id]
        : $id;
    }

    return ['' => $labels];
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

    return isset($this->labels[$id])
      ? $this->labels[$id]
      : $id;
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {

    return isset($this->schemas[$id])
      ? $this->schemas[$id]
      : NULL;
  }
}
