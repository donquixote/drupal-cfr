<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\Legend\LegendInterface;

class Configurator_OrderedIdsTabledrag_Legend extends Configurator_OrderedIdsTabledragBase {

  /**
   * @var \Drupal\cfrapi\Legend\LegendInterface
   */
  private $legend;

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   */
  public function __construct(LegendInterface $legend) {
    $this->legend = $legend;
  }

  /**
   * @return string[]
   *   Format: $[$key] = $label
   */
  protected function getOptions(): array {
    $options = [];
    foreach ($this->legend->getSelectOptions() as $keyOrGroupLabel => $groupOrLabel) {
      if (!is_array($groupOrLabel)) {
        $options[$keyOrGroupLabel] = $groupOrLabel;
      }
      else {
        $options += $groupOrLabel;
      }
    }
    return $options;
  }
}
