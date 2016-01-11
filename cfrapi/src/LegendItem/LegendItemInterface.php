<?php

namespace Drupal\cfrapi\LegendItem;

interface LegendItemInterface {

  /**
   * @return string
   */
  function getLabel();

  /**
   * @return string|null
   */
  function getGroupLabel();

  /**
   * Creates a clone of this legend item, with different labels.
   *
   * @param string $label
   * @param string $groupLabel
   *
   * @return static
   */
  function withLabels($label, $groupLabel);

}
