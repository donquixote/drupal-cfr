<?php

namespace Donquixote\Cf\Schema\Group;

use Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface;

abstract class CfSchema_GroupBase implements CfSchema_GroupInterface {

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
   * @param \Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(CfrSchemaReplacerInterface $replacer) {

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
