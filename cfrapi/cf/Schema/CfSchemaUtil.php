<?php

namespace Donquixote\Cf\Schema;

final class CfSchemaUtil {

  /**
   * @var self|null
   */
  private static $schemaIsUnknown;

  /**
   * @return self
   */
  public static function schemaIsUnknown() {
    return self::$schemaIsUnknown !== NULL
      ? self::$schemaIsUnknown
      : self::$schemaIsUnknown = new self();
  }

}
