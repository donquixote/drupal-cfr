<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\Legend\LegendInterface;

abstract class Configurator_LegendSelectBase extends Configurator_SelectBase {

  /**
   * @var \Drupal\cfrapi\EnumMap\EnumMapInterface
   */
  private $legend;

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
