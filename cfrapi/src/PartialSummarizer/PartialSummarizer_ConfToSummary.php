<?php

namespace Drupal\cfrapi\PartialSummarizer;

use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;

/**
 * @Cf
 *
 * @todo This belongs into the Drupal module.
 */
class PartialSummarizer_ConfToSummary implements PartialSummarizerInterface {

  /**
   * @var \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface
   */
  private $schema;

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $schema
   */
  public function __construct(ConfToSummaryInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    return $this->schema->confGetSummary(
      $conf,
      new SummaryBuilder_Static());
  }
}
