<?php

namespace Drupal\cfrapi\Util;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\Context\CfrContextInterface;

final class ContextReflectionUtil extends UtilBase {

  /**
   * @param \ReflectionParameter[] $params
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return mixed[]|null
   *   Serial arguments array.
   */
  static function paramsContextGetArgs(array $params, CfrContextInterface $context) {
    $serialArgs = array();
    foreach ($params as $i => $param) {
      if ($context->paramValueExists($param)) {
        $arg = $context->paramGetValue($param);
      }
      elseif ($param->isOptional()) {
        $arg = $param->getDefaultValue();
      }
      else {
        return new BrokenValue(NULL, get_defined_vars(), 'Cannot resolve argument. ' . __METHOD__);
      }
      $serialArgs[] = $arg;
    }
    return $serialArgs;
  }

  /**
   * @param \ReflectionParameter[] $params
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   * @param array $namedArgs
   *   Named arguments array.
   *
   * @return mixed[]|null
   *   Serial arguments array, or NULL if not possible.
   */
  static function paramsContextNamedArgsGetArgs(array $params, CfrContextInterface $context, array $namedArgs) {
    $serialArgs = array();
    foreach ($params as $i => $param) {
      $k = $param->getName();
      if (array_key_exists($i, $namedArgs)) {
        $arg = $namedArgs[$i];
      }
      elseif (array_key_exists($k, $namedArgs)) {
        $arg = $namedArgs[$k];
      }
      elseif ($context->paramValueExists($param)) {
        $arg = $context->paramGetValue($param);
      }
      elseif ($param->isOptional()) {
        $arg = $param->getDefaultValue();
      }
      else {
        return new BrokenValue(NULL, get_defined_vars(), 'Cannot resolve argument. ' . __METHOD__);
      }
      $serialArgs[] = $arg;
    }
    return $serialArgs;
  }

}
