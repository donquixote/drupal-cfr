<?php

namespace Drupal\cfrapi\PhpProvider;

use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;
use Drupal\cfrapi\Util\UtilBase;

final class PhpProviderUtil extends UtilBase {

  /**
   * @param mixed $object
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function objGetPhp($object) {
    if (!$object instanceof PhpProviderInterface) {
      $class = get_class($object);
      throw new PhpGenerationNotSupportedException("Object of class '$class' does not support code generation.");
    }
  }

}
