<?php

namespace Drupal\cfrapi\CfrSchema\ValueToValue;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

abstract class ValueToValueSchemaBase implements ValueToValueSchemaInterface {

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
   */
  public function getDecorated() {
    return $this->cfrSchema;
  }
}
