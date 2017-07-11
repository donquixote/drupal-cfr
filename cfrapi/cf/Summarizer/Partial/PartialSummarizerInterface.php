<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

interface PartialSummarizerInterface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper);

}
