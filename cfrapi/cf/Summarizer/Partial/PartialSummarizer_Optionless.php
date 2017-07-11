<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

abstract class PartialSummarizer_Optionless implements PartialSummarizerInterface {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface
   */
  public static function create(CfSchema_OptionlessInterface $schema) {
    return new PartialSummarizer_Null();
  }
}
