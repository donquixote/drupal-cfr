<?php

namespace Drupal\cfrapi\ConfToPhp;

use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;
use Drupal\cfrapi\Util\UtilBase;

final class ConfToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param mixed $conf
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public static function objConfGetPhp($object, $conf) {
    if (!$object instanceof ConfToPhpInterface) {
      if (!is_object($object)) {
        $type = gettype($object);
        throw new PhpGenerationNotSupportedException("Variable of type '$type' is not an object.");
      }
      throw new PhpGenerationNotSupportedException("Object of class '$class' does not support code generation.");
    }
    return $object->confGetPhp($conf);
  }

}
