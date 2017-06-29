<?php

namespace Drupal\cfrapi\SchemaConfToValue;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface SchemaConfToValueInterace {

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param mixed $conf
   * @param \Drupal\cfrapi\SchemaConfToValue\SchemaConfToValueInterace $schemaConfToValue
   *
   * @return mixed
   */
  public function schemaConfGetValue(CfrSchemaInterface $cfrSchema, $conf, SchemaConfToValueInterace $schemaConfToValue);

}
