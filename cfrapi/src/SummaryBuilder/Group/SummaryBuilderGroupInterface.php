<?php

namespace Drupal\cfrapi\SummaryBuilder\Group;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;

interface SummaryBuilderGroupInterface {

  /**
   * @param string $label
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param mixed $conf
   *
   * @return $this
   */
  public function addSetting($label, ConfToSummaryInterface $confToSummary, $conf);

  /**
   * @return mixed
   */
  public function buildSummary();

}
