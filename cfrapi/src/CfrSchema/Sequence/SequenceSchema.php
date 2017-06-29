<?php

namespace Drupal\cfrapi\CfrSchema\Sequence;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

class SequenceSchema implements SequenceSchemaInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  private $itemSchema;

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $itemSchema
   */
  public function __construct(CfrSchemaInterface $itemSchema) {
    $this->itemSchema = $itemSchema;
  }

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  public function getItemSchema() {
    return $this->itemSchema;
  }
}
