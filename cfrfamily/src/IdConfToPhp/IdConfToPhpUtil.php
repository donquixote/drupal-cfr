<?php

namespace Drupal\cfrfamily\IdConfToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Util\UtilBase;

final class IdConfToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param string|int $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public static function objIdConfGetPhp($object, $id, $conf, CodegenHelperInterface $helper) {

    if (!$object instanceof IdConfToPhpInterface) {
      return $helper->notSupported($object, ['id' => $id, 'options' => $conf], "Object does not implement IdConfToPhpInterface.");
    }

    return $object->idConfGetPhp($id, $conf, $helper);
  }

}
