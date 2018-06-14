<?php

namespace Drupal\cfrapi\SummaryBuilder;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;

interface SummaryBuilderInterface {

  /**
   * @param string $label
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $optionsConfToSummary
   * @param $optionsConf
   *
   * @return mixed
   */
  public function idConf($label, ConfToSummaryInterface $optionsConfToSummary, $optionsConf);

  /**
   * Starts a group of named settings.
   *
   * @return \Drupal\cfrapi\SummaryBuilder\Group\SummaryBuilderGroupInterface
   */
  public function startGroup();

  /**
   * Starts a group of unnamed settings.
   *
   * @return \Drupal\cfrapi\SummaryBuilder\Inline\SummaryBuilderInlineInterface
   */
  public function startInline();

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param array $confItems
   *
   * @return mixed
   */
  public function buildSequence(ConfToSummaryInterface $confToSummary, array $confItems);

}
