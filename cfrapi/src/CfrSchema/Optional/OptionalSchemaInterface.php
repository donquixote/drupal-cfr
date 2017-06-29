<?php

namespace Drupal\cfrapi\CfrSchema\Optional;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface OptionalSchemaInterface extends CfrSchemaInterface {

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   *   The non-optional version.
   */
  public function getCfrSchema();

}
