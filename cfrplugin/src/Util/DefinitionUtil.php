<?php

namespace Drupal\cfrplugin\Util;

use Drupal\cfrapi\Util\UtilBase;

final class DefinitionUtil extends UtilBase {

  /**
   * @param array $definition
   *
   * @return null|string
   */
  public static function definitionGetFile(array $definition) {

    foreach ([
      'configurator_class',
      'handler_class',
      'class',
    ] as $k) {
      if (isset($definition[$k])) {
        return self::classGetFile($definition[$k]);
      }
    }

    foreach ([
      'configurator_factory',
      'handler_factory',
      'factory',
    ] as $k) {
      if (isset($definition[$k])) {
        return self::factoryGetFile($definition[$k]);
      }
    }

    foreach ([
      'configurator',
      'handler',
    ] as $k) {
      if (isset($definition[$k])) {
        return self::objectGetFile($definition[$k]);
      }
    }

    return NULL;
  }

  /**
   * @param mixed $object
   *
   * @return null|string
   */
  private static function objectGetFile($object) {
    if (!\is_object($object)) {
      return NULL;
    }
    $class = \get_class($object);
    return self::classGetFile($class);
  }

  /**
   * @param mixed $factory
   *
   * @return null|string
   */
  private static function factoryGetFile($factory) {

    if (\is_array($factory)) {
      if (!isset($factory[0], $factory[1]) || !\is_string($factory[0])) {
        return NULL;
      }
      return self::classGetFile($factory[0]);
    }

    if (\is_string($factory)) {
      list($class, $methodName) = explode('::', $factory) + [NULL, NULL];
      if (NULL === $methodName) {
        try {
          $reflectionFunction = new \ReflectionFunction($factory);
        }
        catch (\ReflectionException $e) {
          // Function does not exist.
          return NULL;
        }
        return $reflectionFunction->getFileName() ?: NULL;
      }

      return self::classGetFile($class);
    }

    return NULL;
  }

  /**
   * @param string $class
   *
   * @return string|null
   */
  private static function classGetFile($class) {

    try {
      $reflectionClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      // Class does not exist.
      return NULL;
    }

    return $reflectionClass->getFileName() ?: NULL;
  }

}
