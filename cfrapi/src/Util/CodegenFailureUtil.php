<?php

namespace Drupal\cfrapi\Util;

use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Exception\MalformedValueException;
use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;

final class CodegenFailureUtil extends UtilBase {

  /**
   * @param string $class
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function cannotExportObject(
    /** @noinspection PhpUnusedParameterInspection */ $class) {
    throw new PhpGenerationNotSupportedException("Cannot export object to PHP.");
  }

  /**
   * @param string $message
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function cannotExportClosure($message) {
    throw new PhpGenerationNotSupportedException($message);
  }

  /**
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function recursionDetected() {
    throw new PhpGenerationNotSupportedException("Recursion detected.");
  }

  /**
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function recursiveArray() {
    throw new PhpGenerationNotSupportedException("Cannot export recursive arrays.");
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public static function incompatibleConfiguration(
    /** @noinspection PhpUnusedParameterInspection */ $conf, $message) {
    throw new InvalidConfigurationException($message);
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   */
  public static function notSupported(
    /** @noinspection PhpUnusedParameterInspection */ $conf, $message) {
    throw new PhpGenerationNotSupportedException($message);
  }

  /**
   * @param string|int $key
   * @param string|null $message
   *
   * @return string
   */
  public static function missingKey($key, $message = NULL) {

    if (NULL === $message) {
      $message = "Missing key '$key'";
    }

    return self::exception(
      MalformedValueException::class,
      $message);
  }

  /**
   * @param mixed $value
   * @param string $message
   *
   * @return string
   */
  public static function malformedValue(/** @noinspection PhpUnusedParameterInspection */ $value, $message) {
    return self::exception(MalformedValueException::class, $message);
  }

  /**
   * @param string $exceptionClass
   * @param string $message
   *
   * @return string
   */
  public static function exception($exceptionClass, $message) {

    $messagePhp = var_export($message, TRUE);

    return <<<EOT
// @todo Fix the generated code manually.
call_user_func(
  function(){
    throw new \\$exceptionClass($messagePhp)
  });
EOT;
  }

}
