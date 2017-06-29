<?php

namespace Drupal\cfrapi\CfrSchema\Sequence;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface SequenceSchemaInterface extends CfrSchemaInterface {

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  public function getItemSchema();

}
