<?php

namespace Donquixote\Cf\Summarizer\Helper;

use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Helper\SchemaHelperBase;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Donquixote\Cf\Util\HtmlUtil;

class SummaryHelper extends SchemaHelperBase implements SummaryHelperInterface {

  /**
   * @var \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface
   */
  private $partialSummarizer;

  /**
   * @var \stdClass
   */
  private $unknownSchemaSymbol;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface
   */
  private $emptynessHelper;

  /**
   * @param \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface $partialSummarizer
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $emptynessHelper
   */
  public function __construct(
    PartialSummarizerInterface $partialSummarizer,
    EmptynessHelperInterface $emptynessHelper
  ) {
    $this->partialSummarizer = $partialSummarizer;
    // Object pointers are unique.
    $this->unknownSchemaSymbol = new \stdClass();
    $this->emptynessHelper = $emptynessHelper;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf) {
    return $this->emptynessHelper->schemaConfIsEmpty($schema, $conf);
  }

  /**
   * @return mixed
   */
  public function unknownSchema() {
    return $this->unknownSchemaSymbol;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf) {

    $value = $this->partialSummarizer->schemaConfGetSummary($schema, $conf, $this);

    if ($this->unknownSchema() === $value) {
      return $this->translate('Unsupported schema.');
    }

    return $value;
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   */
  public function incompatibleConfiguration($conf, $message) {
    return $this->translate('Incompatible configuration')
      . ': '
      . HtmlUtil::sanitize($message);
  }

  /**
   * @param string $message
   *
   * @return mixed
   */
  public function invalidConfiguration($message) {
    return $this->translate('Invalid configuration')
      . ': '
      . HtmlUtil::sanitize($message);
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public function translate($string) {
    // @todo Use injected translation service.
    // @todo Define if return value is safe for HTML.
    return $string;
  }
}
