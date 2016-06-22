<?php

namespace Drupal\cfrfamily\IdPhpToPhp;

use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;
use Drupal\cfrapi\Util\UtilBase;

final class IdPhpToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param string|int $id
   * @param string $php
   *
   * @return string
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function objIdPhpGetPhp($object, $id, $php) {
    if (!$object instanceof IdPhpToPhpInterface) {
      $class = get_class($object);
      throw new PhpGenerationNotSupportedException("Object of class '$class' does not support code generation.");
    }
    return $object->idPhpGetPhp($id, $php);
  }

}
