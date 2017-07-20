<?php

namespace Donquixote\Cf\Schema\Group;

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
   */
  public function __construct(array $schemas, array $labels) {
    $this->schemas = $schemas;
    $this->labels = $labels;
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
