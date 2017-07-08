<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

class PartialSummarizer_NoKnownSchema implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return string|null
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf, SummaryHelperInterface $helper) {
    return $helper->unknownSchema();
  }
}
