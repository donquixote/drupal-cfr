<?php

namespace Drupal\cfrreflection\Util;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\BrokenValue\BrokenValue_CallbackReflectionArg;
use Drupal\cfrapi\BrokenValue\BrokenValue_CallbackReflectionArgs;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\Util\UtilBase;

final class CfrReflectionUtil extends UtilBase {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param mixed[] $args
   *
   * @return null|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  public static function callbackArgsInvalid(CallbackReflectionInterface $callback, array $args) {

    foreach ($args as $arg) {
      if ($arg instanceof BrokenValueInterface) {
        // This is a programming error, because the calling code is responsible to clear the arguments.
        return new BrokenValue_CallbackReflectionArgs($callback, $args, 'Callback arguments must not contain BrokenValue objects.');
      }
    }

    $params = $callback->getReflectionParameters();

    if (array_keys($params) !== array_keys($args)) {
      return new BrokenValue_CallbackReflectionArgs($callback, $args, 'Wrong argument count.');
    }

    foreach ($callback->getReflectionParameters() as $i => $param) {
      $arg = $args[$i];

      if ($param->isOptional()) {
        if ($arg === $param->getDefaultValue()) {
          return NULL;
        }
      }

      if ($param->isArray()) {
        if (!is_array($arg)) {
          # dpm('Param must be array.', __METHOD__);
          return new BrokenValue_CallbackReflectionArg($callback, $args, $i, "Argument $i must be an array.");
        }
      }

      if ($paramClass = $param->getClass()) {
        if (!is_object($arg)) {
          return new BrokenValue_CallbackReflectionArg($callback, $args, $i, "Argument $i must be an object.");
        }
        if (!$paramClass->isInstance($arg)) {
          $argExport = var_export($arg, TRUE);
          $paramClassExport = var_export($paramClass->getName(), TRUE);
          return new BrokenValue_CallbackReflectionArg($callback, $args, $i, "Argument $i must implement $paramClassExport, found $argExport.");
        }
      }
    }

    return NULL;
  }

}
