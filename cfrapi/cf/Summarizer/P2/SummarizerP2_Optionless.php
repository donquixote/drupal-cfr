<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Util\UtilBase;

final class SummarizerP2_Optionless extends UtilBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ) {
    return new SummarizerP2_Null();
  }
}
