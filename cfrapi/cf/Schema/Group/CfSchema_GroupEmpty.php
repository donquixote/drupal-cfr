<?php

namespace Donquixote\Cf\Schema\Group;

use Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface;

class CfSchema_GroupEmpty extends CfSchema_Group_PassthruBase {

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
   * @param \Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(CfrSchemaReplacerInterface $replacer) {
    return $this;
  }
}
