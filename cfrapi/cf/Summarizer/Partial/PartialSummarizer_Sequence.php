<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

/**
 * @Cf
 */
class PartialSummarizer_Sequence implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   */
  public function __construct(CfSchema_SequenceInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    $itemSchema = $this->schema->getItemSchema();

    if (!is_array($conf)) {
      $conf = [];
    }

    $summary = '';
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return '- ' . $helper->translate('Noisy configuration') . ' -';
      }

      $itemSummary = $helper->schemaConfGetSummary($itemSchema, $itemConf);

      if (is_string($itemSummary) && '' !== $itemSummary) {
        $summary .= "<li>$itemSummary</li>";
      }
    }

    if ('' === $summary) {
      return NULL;
    }

    return "<ol>$summary</ol>";
  }
}
