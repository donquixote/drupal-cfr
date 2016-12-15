<?php

namespace Drupal\cfrapi\PhpProvider;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Util\UtilBase;

final class PhpProviderUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   */
  public static function objGetPhp($object, CodegenHelperInterface $helper) {

    if (!$object instanceof PhpProviderInterface) {
      return $helper->notSupported($object, NULL, "Object does not implement PhpProviderInterface.");
    }

    return $object->getPhp($helper);
  }

}
