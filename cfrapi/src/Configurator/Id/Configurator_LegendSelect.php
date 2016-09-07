<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\Legend\LegendInterface;

class Configurator_LegendSelect extends Configurator_SelectBase {

  /**
   * @var \Drupal\cfrapi\EnumMap\EnumMapInterface
   */
  private $legend;

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

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param bool $required
   * @param string|null $defaultId
   */
  public function __construct(LegendInterface $legend, $required = TRUE, $defaultId = NULL) {
    $this->legend = $legend;
    parent::__construct($required, $defaultId);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {
    return $this->legend->getSelectOptions();
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {
    return $this->legend->idGetLabel($id);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  protected function idIsKnown($id) {
    return $this->legend->idIsKnown($id);
  }
}
