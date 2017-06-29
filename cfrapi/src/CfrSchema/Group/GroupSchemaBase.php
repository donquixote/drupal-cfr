<?php

namespace Drupal\cfrapi\CfrSchema\Group;

abstract class GroupSchemaBase implements GroupSchemaInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[]
   */
  private $schemas;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[] $schemas
   * @param string[] $labels
   */
  public function __construct(array $schemas, array $labels) {
    $this->schemas = $schemas;
    $this->labels = $labels;
  }

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[]
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
