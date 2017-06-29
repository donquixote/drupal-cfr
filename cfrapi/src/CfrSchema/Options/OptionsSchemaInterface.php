<?php

namespace Drupal\cfrapi\CfrSchema\Options;

use Drupal\cfrapi\Legend\LegendInterface;

interface OptionsSchemaInterface extends LegendInterface {

  /**
   * @return bool
   */
  public function isOptional();

}
