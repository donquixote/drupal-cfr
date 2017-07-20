<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialSummarizer_Options implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   */
  public function __construct(CfSchema_OptionsInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return mixed
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $helper->invalidConfiguration('Required id empty for options schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' for options schema.");
    }

    return $this->schema->idGetLabel($id);
  }
}
