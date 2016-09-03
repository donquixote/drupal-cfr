<?php

namespace Drupal\cfrapi\SummaryBuilder\Inline;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class SummaryBuilderInline_Static implements SummaryBuilderInlineInterface {

  /**
   * @var \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface
   */
  private $summaryBuilder;

  /**
   * @var string
   */
  private $separator;

  /**
   * @var string[]
   */
  private $pieces = [];

  /**
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   * @param string $separator
   */
  function __construct(SummaryBuilderInterface $summaryBuilder, $separator = ', ') {
    $this->summaryBuilder = $summaryBuilder;
    $this->separator = $separator;
  }

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param mixed $conf
   *
   * @return $this
   */
  function addSetting(ConfToSummaryInterface $confToSummary, $conf) {
    $this->pieces[] = $confToSummary->confGetSummary($conf, $this->summaryBuilder);

    return $this;
  }

  /**
   * @return mixed
   */
  function buildSummary() {
    return implode($this->separator, $this->pieces);
  }
}
