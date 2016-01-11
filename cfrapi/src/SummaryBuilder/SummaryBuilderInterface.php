<?php

namespace Drupal\cfrapi\SummaryBuilder;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;

interface SummaryBuilderInterface {

  /**
   * @param $label
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $optionsConfToSummary
   * @param $optionsConf
   *
   * @return mixed
   */
  function idConf($label, ConfToSummaryInterface $optionsConfToSummary = NULL, $optionsConf);

  /**
   * Starts a group of named settings.
   *
   * @return \Drupal\cfrapi\SummaryBuilder\Group\SummaryBuilderGroupInterface
   */
  function startGroup();

  /**
   * Starts a group of unnamed settings.
   *
   * @return \Drupal\cfrapi\SummaryBuilder\Inline\SummaryBuilderInlineInterface
   */
  function startInline();

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param array $confItems
   *
   * @return mixed
   */
  function buildSequence(ConfToSummaryInterface $confToSummary, array $confItems);

}
