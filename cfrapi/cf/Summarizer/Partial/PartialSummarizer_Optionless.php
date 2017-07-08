<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

class PartialSummarizer_Optionless implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf, SummaryHelperInterface $helper) {

    if (!$schema instanceof CfSchema_OptionlessInterface) {
      return $helper->unknownSchema();
    }

    return NULL;
  }
}
