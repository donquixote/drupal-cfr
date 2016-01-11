<?php

namespace Drupal\cfrfamily\LegendItem;

use Drupal\cfrapi\LegendItem\LegendItem;
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
  function __construct(CfrLegendItemInterface $decorated, $groupLabel) {
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
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
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
  function confGetForm($conf, $label) {
    return $this->decorated->confGetForm($conf, $label);
  }

  /**
   * @return string
   */
  function getLabel() {
    return $this->decorated->getLabel();
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
    return new LegendItem($label, $groupLabel);
  }
}
