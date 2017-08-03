<?php

namespace Donquixote\Cf\Util;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;

final class ReflectionUtil extends UtilBase {

  const FQCN_PATTERN = '@^(\\\\[a-zA-Z_][a-zA-Z_0-9]*)+$@';

  const PRIMITIVE_TYPES = ['boolean', 'bool', 'integer', 'double', 'float', 'string', 'array', 'object', 'resource', 'null', 'false', 'true', 'callable'];

  /**
   * @param callable $callable
   *
   * @return string[]
   */
  public static function callableGetReturnTypeNames($callable) {

    if (NULL !== $reflFunction = self::callableGetReflectionFunction($callable)) {
      return self::functionGetReturnTypeNames($reflFunction);
    }

    return [];
  }

  /**
   * @param callable $callable
   *
   * @return \ReflectionFunctionAbstract
   */
  public static function callableGetReflectionFunction($callable) {

    if ($callable instanceof \Closure) {
      return new \ReflectionFunction($callable);
    }

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
        list($class, $methodName) = explode('::', $callable);
        if (!method_exists($class, $methodName)) {
          return NULL;
        }

        return new \ReflectionMethod($class, $callable);
      }
    }

    if (is_array($callable)) {
      return new \ReflectionMethod($callable[0], $callable[1]);
    }

    if (is_object($callable)) {
      if (method_exists($callable, '__invoke')) {
        return new \ReflectionMethod($callable, '__invoke');
      }

      return NULL;
    }

    return NULL;
  }

  public static function closureGetReturnTypeNames(\Closure $closure) {
    $reflFunction = new \ReflectionFunction($closure);
    return self::functionGetReturnTypeNames($reflFunction);
  }

  /**
   * @param \ReflectionFunctionAbstract $function
   *
   * @return string[]
   */
  public static function functionGetReturnTypeNames(\ReflectionFunctionAbstract $function) {

    if (method_exists($function, 'getReturnType')) {
      // This is PHP 7.
      if (NULL !== $returnType = $function->getReturnType()) {
        return [$returnType->getName()];
      }
    }

    if (NULL === $docComment = $function->getDocComment()) {
      return [];
    }

    if ($function instanceof \ReflectionMethod) {
      $declaringClass = $function->getDeclaringClass();
      $declaringClassName = $declaringClass->getName();
      $aliasMap = [];
      $namespace = $declaringClass->getNamespaceName();
    }
    else {
      $declaringClassName = NULL;
      $aliasMap = [];
      $namespace = NULL;
    }

    return self::docGetReturnTypeClassNames(
      $docComment,
      $declaringClassName,
      $aliasMap,
      $namespace);
  }

  /**
   * @param string $docComment
   * @param string|null $selfClassName
   * @param string[] $aliasMap
   *   Format: $[$alias] = $class
   * @param string|null $namespace
   *
   * @return string[]
   */
  public static function docGetReturnTypeClassNames(
    $docComment,
    $selfClassName = NULL,
    array $aliasMap = [],
    $namespace = NULL
  ) {

    if (NULL !== $selfClassName) {
      $aliasMap['$this'] = $aliasMap['self'] = $aliasMap['static'] = $selfClassName;
    }

    $names = [];
    foreach (self::docGetReturnTypeAliases($docComment) as $alias) {

      if (NULL !== $name = self::aliasGetClassName($alias, $aliasMap, $namespace)) {
        $names[] = $name;
      }
    }

    return $names;
  }

  /**
   * @param string $docComment
   *
   * @return string[]
   */
  public static function docGetReturnTypeAliases($docComment) {

    if (!preg_match('~(?:^/\*\*\ +|\v\h*\* )@return\h+(\S+)~', $docComment, $m)) {
      return [];
    }

    $aliases = [];
    foreach (explode('|', $m[1]) as $alias) {

      if ('' === $alias) {
        continue;
      }

      $aliases[] = $alias;
    }

    return $aliases;
  }

  /**
   * @param string $alias
   * @param string[] $aliasMap
   * @param string|null $namespace
   *
   * @return bool|mixed|null|string
   */
  public static function aliasGetClassName($alias, array $aliasMap = [], $namespace = NULL) {

    if ('' === $alias) {
      return NULL;
    }

    if ('\\' === $alias[0]) {
      // This seems to be an FQCN.

      if (!preg_match(self::FQCN_PATTERN, $alias)) {
        // But it is not.
        return NULL;
      }

      // Oh yes it is!
      return substr($alias, 1);
    }

    if (isset($aliasMap[$alias])) {
      return $aliasMap[$alias];
    }

    if (in_array(strtolower($alias), self::PRIMITIVE_TYPES, TRUE)) {
      // Ignore primitive types.
      return NULL;
    }

    if (NULL === $namespace) {
      // Namespace is not known, we cannot do further magic.
      return NULL;
    }

    if (!preg_match(self::FQCN_PATTERN, '\\' . $alias)) {
      return NULL;
    }

    if ('' === $namespace) {
      return $alias;
    }

    return $namespace . '\\' . $alias;
  }

  /**
   * @param object $object
   * @param string $k
   * @param string|null $context
   *
   * @return mixed
   */
  public static function &objectGetPropertyValueRef($object, $k, $context = null) {

    if (null === $context) {
      /** @var string|object|null $context */
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $context = $object;
    }

    // See https://stackoverflow.com/a/17560595/246724
    $closure = function & ($k) use ($object) {
      // Using $object instead of $this, to prevent IDE warnings.
      return $object->$k;
    };

    $bound = $closure->bindTo(null, $context);

    return $bound->__invoke($k);
  }

  /**
   * @param object $object
   * @param string $k
   * @param string|null $context
   *
   * @return mixed
   */
  public static function objectGetPropertyValue($object, $k, $context = null) {

    if (null === $context) {
      /** @var string|object|null $context */
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $context = $object;
    }

    // See https://stackoverflow.com/a/17560595/246724
    $closure = function ($k) use ($object) {
      // Using $object instead of $this, to prevent IDE warnings.
      return $object->$k;
    };

    $bound = $closure->bindTo(null, $context);

    return $bound->__invoke($k);
  }

  /**
   * @param object $object
   * @param string $methodName
   * @param array $args
   * @param string|null $context
   *
   * @return mixed
   */
  public static function objectCallMethodArgs($object, $methodName, array $args, $context = null) {

    if (null === $context) {
      /** @var string|object|null $context */
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $context = $object;
    }

    $reflMethod = new \ReflectionMethod($context, $methodName);

    $accessible = !$reflMethod->isProtected() && !$reflMethod->isPrivate();

    if (!$accessible) {
      $reflMethod->setAccessible(TRUE);
      $return = $reflMethod->invokeArgs($object, $args);
      $reflMethod->setAccessible(FALSE);
    }
    else {
      $return = $reflMethod->invokeArgs($object, $args);
    }

    return $return;
  }

  /**
   * @param \ReflectionParameter[] $params
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return mixed[]|null
   */
  public static function paramsGetValues(array $params, ParamToValueInterface $paramToValue) {

    $else = new \stdClass();

    $argValues = [];
    foreach ($params as $i => $param) {
      if ($else === $argValue = $paramToValue->paramGetValue($param, $else)) {
        return NULL;
      }
      $argValues[$i] = $argValue;
    }

    return $argValues;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   * @param mixed $else
   *
   * @return mixed
   */
  public static function callbackInvokePTV(
    CallbackReflectionInterface $callback,
    ParamToValueInterface $paramToValue,
    $else = NULL
  ) {

    $args = self::paramsGetValues(
      $callback->getReflectionParameters(),
      $paramToValue);

    if (NULL === $args) {
      return $else;
    }

    return $callback->invokeArgs($args);
  }

}
