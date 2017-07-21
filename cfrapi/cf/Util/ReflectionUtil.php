<?php

namespace Donquixote\Cf\Util;

final class ReflectionUtil extends UtilBase {

  const FQCN_PATTERN = '@^(\\\\[a-zA-Z_][a-zA-Z_0-9]*)+$@';

  const PRIMITIVE_TYPES = ['boolean', 'bool', 'integer', 'double', 'float', 'string', 'array', 'object', 'resource', 'null', 'false', 'true', 'callable'];

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

}
