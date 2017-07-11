<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Emptyness\EmptynessInterface;

class PartialSummarizer_OptionalWithEmptyness implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    EmptynessInterface $emptyness
  ) {
    $this->schema = $schema;
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return string|null
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    if ($this->emptyness->confIsEmpty($conf)) {

      if (NULL === $summaryUnsafe = $this->schema->getEmptySummary()) {
        return NULL;
      }

      // The schema's summary might not be designed for HTML.
      return HtmlUtil::sanitize($summaryUnsafe);
    }

    return $helper->schemaConfGetSummary(
      $this->schema->getDecorated(),
      $conf);
  }
}
