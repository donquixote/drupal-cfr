<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

class PartialSummarizer_Optional implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $optionalSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetSummary(CfSchemaInterface $optionalSchema, $conf, SummaryHelperInterface $helper) {

    if (!$optionalSchema instanceof CfSchema_OptionalInterface) {
      return $helper->unknownSchema();
    }

    $decoratedSchema = $optionalSchema->getDecorated();

    list($enabled, $conf) = $helper->schemaConfGetStatusAndOptions(
      $decoratedSchema, $conf);

    if ($enabled) {
      return $helper->schemaConfGetSummary($decoratedSchema, $conf);
    }

    if (NULL !== $summary = $optionalSchema->getEmptySummary()) {
      return $summary;
    }

    return $helper->translate('None');
  }
}
