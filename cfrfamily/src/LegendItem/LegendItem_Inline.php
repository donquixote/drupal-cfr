<?php

namespace Drupal\cfrfamily\LegendItem;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface;

class LegendItem_Inline implements CfrLegendItemInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface
   */
  private $decorated;

  /**
   * @var string
   */
  private $groupLabel;

  /**
   * @param \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface $decorated
   * @param $groupLabel
   */
  public function __construct(CfrLegendItemInterface $decorated, $groupLabel) {
    $this->decorated = $decorated;
    $this->groupLabel = $groupLabel;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->decorated->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return $this->decorated->confGetForm($conf, $label);
  }

  /**
   * @return string
   */
  public function getLabel() {
    return $this->decorated->getLabel();
  }

  /**
   * @return string|null
   */
  public function getGroupLabel() {
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
  public function withLabels($label, $groupLabel) {
    return new self($this->decorated->withLabels($label, '?'), $groupLabel);
  }
}
