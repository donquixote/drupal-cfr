<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;

/**
 * @todo This belongs into the Drupal module.
 */
class PartialSummarizer_ConfToSummary implements PartialSummarizerInterface {

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
    if (!$schema instanceof ConfToSummaryInterface) {
      return $helper->unknownSchema();
    }

    return $schema->confGetSummary($conf, new SummaryBuilder_Static());
  }
}
