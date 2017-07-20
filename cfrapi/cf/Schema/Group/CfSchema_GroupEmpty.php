<?php

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class CfSchema_GroupEmpty implements CfSchema_GroupInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas() {
    return [];
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    return [];
  }

  /**
   * Returns a version of this schema where internal schemas are replaced by
   * "better" ones, recursively.
   *
   * A schema is considered "better" if it is closer to actual implementation.
   *
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(SchemaReplacerInterface $replacer) {
    return $this;
  }
}
