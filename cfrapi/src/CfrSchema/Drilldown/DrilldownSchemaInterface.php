<?php

namespace Drupal\cfrapi\CfrSchema\Drilldown;

use Drupal\cfrapi\CfrSchema\Options\OptionsSchemaInterface;

interface DrilldownSchemaInterface extends OptionsSchemaInterface {

  /**
   * @param string|int $id
   *
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface|null
   */
  public function idGetCfrSchema($id);

}
