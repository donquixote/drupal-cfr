<?php

namespace Drupal\cfrapi\LegendItem;

class LegendItem implements LegendItemInterface {

  /**
   * @var string
   */
  private $label;

  /**
   * @var string
   */
  private $groupLabel;

  /**
   * @param string $label
   * @param string $groupLabel
   */
  function __construct($label, $groupLabel) {
    $this->label = $label;
    $this->groupLabel = $groupLabel;
  }

  /**
   * @return string
   */
  function getLabel() {
    return $this->label;
  }

  /**
   * @return string|null
   */
  function getGroupLabel() {
    return $this->groupLabel;
  }

  /**
   * Creates a clone of this legend item, with different labels.
   *
   * @param string $label
   * @param string $groupLabel
   *
   * @return static
   */
  function withLabels($label, $groupLabel) {
    return new self($label, $groupLabel);
  }
}
