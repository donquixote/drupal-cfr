<?php

namespace Drupal\cfrfamily\IdPhpToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Util\UtilBase;

final class IdPhpToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param string|int $id
   * @param string $php
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function objIdPhpGetPhp($object, $id, $php, CodegenHelperInterface $helper) {

    if (!$object instanceof IdPhpToPhpInterface) {
      return $helper->notSupported($object, ['id' => $id, 'php' => $php], "Object does not implement IdPhpToPhpInterface.");
    }

    return $object->idPhpGetPhp($id, $php);
  }

}
