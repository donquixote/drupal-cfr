<?php

namespace Drupal\cfrapi\CfrSchema\Optional;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

class OptionalSchema implements OptionalSchemaInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  private $cfrSchema;

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   */
  public function __construct(CfrSchemaInterface $cfrSchema) {
    $this->cfrSchema = $cfrSchema;
  }

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   *   The non-optional version.
   */
  public function getCfrSchema() {
    return $this->cfrSchema;
  }
}
