<?php

namespace Drupal\cfrapi\Util;

use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;

final class CodegenFailureUtil extends UtilBase {

  /**
   * @param string $class
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function cannotExportObject($class) {
    throw new PhpGenerationNotSupportedException($class, "Cannot export object to PHP.");
  }

  /**
   * @param string $message
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function cannotExportClosure($message) {
    throw new PhpGenerationNotSupportedException(NULL, $message);
  }

  /**
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function recursionDetected() {
    throw new PhpGenerationNotSupportedException(NULL, "Recursion detected.");
  }

  /**
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function recursiveArray() {
    throw new PhpGenerationNotSupportedException(NULL, "Cannot export recursive arrays.");
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public static function incompatibleConfiguration($conf, $message) {
    throw new InvalidConfigurationException($conf, $message);
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function notSupported($conf, $message) {
    throw new PhpGenerationNotSupportedException($conf, $message);
  }

}
