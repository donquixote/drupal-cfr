<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

class PartialSummarizer_Null implements PartialSummarizerInterface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return mixed
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {
    return NULL;
  }
}
