<?php

namespace Drupal\cfrfamily\CfrLegendItem;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\LegendItem\LegendItem;

class CfrLegendItem extends LegendItem implements CfrLegendItemInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @param string $label
   * @param string $groupLabel
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   */
  public function __construct($label, $groupLabel, ConfiguratorInterface $configurator) {
    parent::__construct($label, $groupLabel);
    $this->configurator = $configurator;
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
    return $this->configurator->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->configurator->confGetSummary($conf, $summaryBuilder);
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
    return new self($label, $groupLabel, $this->configurator);
  }
}
