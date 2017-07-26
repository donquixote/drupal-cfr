<?php

namespace Donquixote\Cf\Optionlessness;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Util\UtilBase;

final class OptionlessnessSTAs extends UtilBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Optionlessness\OptionlessnessInterface
   */
  public static function optionless(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ) {
    return new Optionlessness(TRUE);
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Optionlessness\OptionlessnessInterface $schema
   *
   * @return \Donquixote\Cf\Optionlessness\Optionlessness
   */
  public static function optionlessness(OptionlessnessInterface $schema) {
    return new Optionlessness($schema->isOptionless());
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Optionlessness\Optionlessness|null
   */
  public static function other(
    /** @noinspection PhpUnusedParameterInspection */ CfSchemaInterface $schema
  ) {
    return new Optionlessness(FALSE);
  }

}
