<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

class PartialSummarizer_Neutral implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary(
    CfSchemaInterface $schema,
    $conf,
    SummaryHelperInterface $helper)
  {
    if (!$schema instanceof CfSchema_NeutralInterface) {
      return $helper->unknownSchema();
    }

    return $helper->schemaConfGetSummary($schema->getDecorated(), $conf);
  }
}
