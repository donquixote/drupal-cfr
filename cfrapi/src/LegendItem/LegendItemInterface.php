<?php

namespace Drupal\cfrapi\LegendItem;

interface LegendItemInterface {

  /**
   * @return string
   */
  public function getLabel();

  /**
   * @return string|null
   */
  public function getGroupLabel();

  /**
   * Creates a clone of this legend item, with different labels.
   *
   * @param string $label
   * @param string $groupLabel
   *
   * @return static
   */
  public function withLabels($label, $groupLabel);

}
