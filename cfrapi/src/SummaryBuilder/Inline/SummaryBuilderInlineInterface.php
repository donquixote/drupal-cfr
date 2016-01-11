<?php

namespace Drupal\cfrapi\SummaryBuilder\Inline;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;

interface SummaryBuilderInlineInterface {

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param mixed $conf
   *
   * @return $this
   */
  function addSetting(ConfToSummaryInterface $confToSummary, $conf);

  /**
   * @return mixed
   */
  function buildSummary();

}
