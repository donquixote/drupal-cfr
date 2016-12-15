<?php

namespace Drupal\cfrapi\ConfToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Util\UtilBase;

final class ConfToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param mixed $conf
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public static function objConfGetPhp($object, $conf, CodegenHelperInterface $helper) {

    if ($object instanceof ConfToPhpInterface) {
      return $object->confGetPhp($conf, $helper);
    }

    if (!is_object($object)) {
      $type = gettype($object);
      return $helper->notSupported($object, $conf, "Variable of type '$type' is not an object.");
    }

    return $helper->notSupported($object, $conf, "Object does not implement ConfToPhpInterface.");
  }

}
