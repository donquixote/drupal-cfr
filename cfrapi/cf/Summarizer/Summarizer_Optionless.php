<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Util\UtilBase;

final class Summarizer_Optionless extends UtilBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ) {
    return new Summarizer_Null();
  }
}
