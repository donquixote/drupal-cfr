<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

class PartialSummarizer_Neutral implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   */
  public function __construct(CfSchema_NeutralInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {
    return $helper->schemaConfGetSummary(
      $this->schema->getDecorated(),
      $conf);
  }
}
