<?php

namespace Drupal\cfrapi\ConfToSummary;

interface OptionableConfToSummaryInterface extends ConfToSummaryInterface {

  /**
   * @return string
   */
  function getEmptySummary();

}
