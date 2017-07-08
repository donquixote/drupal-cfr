<?php

namespace Donquixote\Cf\Schema\Options;

use Drupal\cfrapi\Legend\LegendInterface;

class CfSchema_Options_FromLegend extends CfSchema_Options_PassthruBase {

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
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {

    $options = [];
    foreach ($this->legend->getSelectOptions() as $groupLabelOrKey => $groupOptionsOrLabel) {
      if (is_array($groupOptionsOrLabel)) {
        $options[$groupLabelOrKey] = $groupOptionsOrLabel;
      }
      else {
        $options[''][$groupLabelOrKey] = $groupOptionsOrLabel;
      }
    }

    return $options;
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return $this->legend->idGetLabel($id);
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return $this->legend->idIsKnown($id);
  }
}
