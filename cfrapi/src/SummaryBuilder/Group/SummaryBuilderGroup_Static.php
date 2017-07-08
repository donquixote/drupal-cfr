<?php

namespace Drupal\cfrapi\SummaryBuilder\Group;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class SummaryBuilderGroup_Static implements SummaryBuilderGroupInterface {

  /**
   * @var \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface
   */
  private $summaryBuilder;

  /**
   * @var string
   */
  private $summary = '';

  /**
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   */
  public function __construct(SummaryBuilderInterface $summaryBuilder) {
    $this->summaryBuilder = $summaryBuilder;
  }

  /**
   * @param string $label
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param mixed $conf
   *
   * @return $this
   */
  public function addSetting($label, ConfToSummaryInterface $confToSummary, $conf) {
    $this->summary .= ''
      . '<li>'
      . check_plain($label) . ': '
      . $confToSummary->confGetSummary($conf, $this->summaryBuilder)
      . '</li>';

    return $this;
  }

  /**
   * @return mixed
   */
  public function buildSummary() {
    return '' !== $this->summary
      ? '<ul>' . $this->summary . '</ul>'
      : NULL;
  }
}
