<?php

namespace Drupal\cfrapi\CfrSchema;

final class CfrSchemaUtil {

  /**
   * @var self|null
   */
  static $schemaIsUnknown;

  /**
   * @return self
   */
  public static function schemaIsUnknown() {
    return self::$schemaIsUnknown !== NULL
      ? self::$schemaIsUnknown
      : self::$schemaIsUnknown = new self();
  }

}
