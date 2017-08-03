<?php

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class CfSchema_Group implements CfSchema_GroupInterface {

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
  public function __construct(array $schemas, array $labels) {

    foreach ($schemas as $k => $itemSchema) {
      if (!$itemSchema instanceof CfSchemaInterface) {
        throw new \InvalidArgumentException("Item schema at key $k must be instance of CfSchemaInterface.");
      }
    }

    $this->schemas = $schemas;
    $this->labels = $labels;
  }

  /**
   * @param string $key
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param null $label
   *
   * @return \Donquixote\Cf\Schema\Group\CfSchema_Group
   */
  public function withItem($key, CfSchemaInterface $schema, $label = NULL) {
    $clone = clone $this;
    $clone->schemas[$key] = $schema;
    $clone->labels[$key] = NULL !== $label
      ? $label
      : $key;
    return $clone;
  }

  /**
   * Returns a version of this schema where internal schemas are replaced,
   * recursively.
   *
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(SchemaReplacerInterface $replacer) {

    $replacements = $this->schemas;
    foreach ($this->schemas as $k => $schema) {
      if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
        $replacements[$k] = $replacement;
      }
    }

    $clone = clone $this;
    $clone->schemas = $replacements;
    return $clone;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas() {
    return $this->schemas;
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    return $this->labels;
  }
}
