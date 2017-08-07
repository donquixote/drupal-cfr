<?php

namespace Donquixote\Cf\Util;

class CallbackUtil {

  /**
   * @param mixed|callable $callable
   *
   * @return \ReflectionFunctionAbstract|null
   */
  public static function callableGetReflector($callable) {

    if (!is_callable($callable)) {
      return NULL;
    }

    if (is_string($callable)) {
      if (FALSE === strpos($callable, '::')) {
        if (!function_exists($callable)) {
          return NULL;
        }
        return new \ReflectionFunction($callable);
      }
      else {
        return new \ReflectionMethod($callable);
      }
    }

    if (is_object($callable)) {
      if ($callable instanceof \Closure) {
        return new \ReflectionFunction($callable);
      }
      if (!method_exists($callable, '__invoke')) {
        return new \ReflectionMethod($callable, '__invoke');
      }
    }

    if (is_array($callable)) {
      if (isset($callable[0], $callable[1])) {
        return new \ReflectionMethod($callable[0], $callable[1]);
      }
    }

    return NULL;
  }

}
