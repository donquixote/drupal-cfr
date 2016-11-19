<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\Legend\LegendInterface;

class Configurator_LegendSelect extends Configurator_LegendSelectBase {

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createRequired(LegendInterface $legend, $defaultId = NULL) {
    return new self($legend, TRUE, $defaultId);
  }

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createOptional(LegendInterface $legend, $defaultId = NULL) {
    return new self($legend, FALSE, $defaultId);
  }
}
