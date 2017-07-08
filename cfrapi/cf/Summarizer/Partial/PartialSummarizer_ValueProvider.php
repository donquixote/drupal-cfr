<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

class PartialSummarizer_ValueProvider implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf, SummaryHelperInterface $helper) {

    if (!$schema instanceof ValueProviderInterface) {
      return $helper->unknownSchema();
    }

    return NULL;
  }
}
