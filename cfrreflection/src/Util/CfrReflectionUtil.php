<?php

namespace Drupal\cfrreflection\Util;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\BrokenValue\BrokenValue;
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
        # dpm(ddebug_backtrace(TRUE), __METHOD__);
        # \Drupal\krumong\dpm($callback, 'CALLBACK');
        # dpm($args, 'ARGS');
        break;
      }
    }

    $params = $callback->getReflectionParameters();

    if (array_keys($params) !== array_keys($args)) {
      # dpm('Wrong arg count', __METHOD__);
      return new BrokenValue(NULL, get_defined_vars(), 'Wrong argument count.');
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
          return new BrokenValue(NULL, get_defined_vars(), 'Param must be array.');
        }
      }

      if ($paramClass = $param->getClass()) {
        if (!is_object($arg)) {
          return new BrokenValue(NULL, get_defined_vars(), 'Parameter must be an object.');
        }
        if (!$paramClass->isInstance($arg)) {
          # dpm('Param type mismatch.', __METHOD__);
          $argExport = var_export($arg, TRUE);
          $paramClassExport = var_export($paramClass->getName(), TRUE);
          return new BrokenValue(NULL, get_defined_vars(), "Expected $paramClassExport, found $argExport");
        }
      }
    }

    return NULL;
  }

}
