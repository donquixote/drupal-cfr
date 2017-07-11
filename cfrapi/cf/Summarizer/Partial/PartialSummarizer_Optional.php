<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\HtmlUtil;

class PartialSummarizer_Optional implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface $schemaToEmptyness
   *
   * @return \Closure
   */
  public static function getFactory(SchemaToEmptynessInterface $schemaToEmptyness) {

    /**
     * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
     *
     * @return \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface
     */
    return function(CfSchema_OptionalInterface $schema) use ($schemaToEmptyness) {

      if (NULL === $emptyness = $schemaToEmptyness->schemaGetEmptyness($schema)) {
        return new self($schema);
      }

      return new PartialSummarizer_OptionalWithEmptyness($schema, $emptyness);
    };
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(CfSchema_OptionalInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    if (!is_array($conf) || empty($conf['enabled'])) {

      if (NULL === $summaryUnsafe = $this->schema->getEmptySummary()) {
        return NULL;
      }

      // The schema's summary might not be designed for HTML.
      return HtmlUtil::sanitize($summaryUnsafe);
    }

    $subConf = isset($conf['options'])
      ? $conf['options']
      : NULL;

    return $helper->schemaConfGetSummary(
      $this->schema->getDecorated(),
      $subConf);
  }
}
