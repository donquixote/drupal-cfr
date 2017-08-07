<?php

namespace Drupal\cfrplugin\Util;

use Donquixote\Cf\Util\CallbackUtil;
use Drupal\cfrapi\Util\UtilBase;

final class UiDefinitionUtil extends UtilBase {

  /**
   * @param array $definition
   *
   * @return null|string
   */
  public static function definitionGetDocComment(array $definition) {

    if (NULL === $reflector = self::definitionGetReflector($definition)) {
      return NULL;
    }

    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getDocComment();
    }

    if ($reflector instanceof \ReflectionFunctionAbstract) {
      return $reflector->getDocComment();
    }

    return NULL;
  }

  /**
   * @param array $definition
   *
   * @return null|string
   */
  public static function definitionGetCodeSnippet(array $definition) {

    if (NULL === $reflector = self::definitionGetReflector($definition)) {
      return NULL;
    }

    if (NULL === $snippet = self::reflectorGetSnippet(
      $reflector,
      TRUE,
      '' /* "\n    [..]\n  " */)
    ) {
      return NULL;
    }

    if (0
      || !$reflector instanceof \ReflectionMethod
      || ! $reflClass = $reflector->getDeclaringClass()
    ) {
      return $snippet;
    }

    $snippet = ''
      . "\n  [..]"
      . "\n"
      . "\n" . $snippet
      . "\n"
      . "\n  [..]"
      . "\n";

    $snippet = self::reflectorGetSnippet(
      $reflClass,
      FALSE,
      $snippet);

    return $snippet;
  }

  /**
   * @param \Reflector $reflector
   * @param bool $withDoc
   * @param string|null $replaceBody
   *
   * @return null|string
   */
  public static function reflectorGetSnippet(\Reflector $reflector, $withDoc = FALSE, $replaceBody = NULL) {

    if (1
      && !$reflector instanceof \ReflectionClass
      && !$reflector instanceof \ReflectionFunctionAbstract
    ) {
      return NULL;
    }

    if (!$file = $reflector->getFileName()) {
      return NULL;
    }

    $start = $reflector->getStartLine() - 1;

    $snippet = self::fileGetLines(
      $file,
      $start,
      $reflector->getEndLine());

    if (FALSE === $pos = strpos($snippet, '{')) {
      return NULL;
    }

    if (NULL !== $replaceBody) {
      $snippet = substr($snippet, 0, $pos)
        . '{' . $replaceBody . '}';
    }

    if (1
      && $withDoc
      && NULL !== $doc = $reflector->getDocComment()
    ) {
      // Extract $doc in a way that includes leading line break and indent.
      $doc = self::fileGetLines(
        $file,
        $start - substr_count($doc, "\n") - 1,
        $start);

      $snippet = $doc . $snippet;
    }

    return $snippet;
  }

  /**
   * @param string $file
   * @param int $start
   * @param int $end
   *
   * @return string
   */
  public static function fileGetLines($file, $start, $end) {

    if (!is_readable($file)) {
      return NULL;
    }

    $f = new \SplFileObject($file);
    $f->seek($start);

    $php = '';
    for ($i = $start; !$f->eof() && $i < $end; $i++) {
      $php .= $f->current();
      $f->next();
    }

    return $php;
  }

  /**
   * @param array $definition
   *
   * @return null|string
   */
  public static function definitionGetClass(array $definition) {

    foreach ([
      'configurator',
      'schema',
      'handler',
    ] as $prefix) {

      if (isset($definition[$key = $prefix . '_class'])) {
        return $definition[$key];
      }

      if (isset($definition[$key = $prefix . '_factory'])) {
        $callback = $definition[$key];
        if (is_array($callback)) {
          if (1
            && isset($callback[0], $callback[1])
            && is_string($callback[0])
          ) {
            return $callback[0];
          }
        }
        elseif (is_string($callback)) {
          if (FALSE !== $pos = strpos($callback, '::')) {
            return substr($callback, 0, $pos);
          }
        }
        else {
          return NULL;
        }
      }
      elseif (isset($definition[$prefix])) {
        $value = $definition[$prefix];
        if (is_object($value)) {
          return get_class($value);
        }
        else {
          return NULL;
        }
      }
    }

    return NULL;
  }

  /**
   * @param array $definition
   *
   * @return null|\Reflector
   */
  public static function definitionGetReflector(array $definition) {

    foreach ([
      'configurator',
      'schema',
      'handler',
    ] as $prefix) {

      if (isset($definition[$key = $prefix . '_class'])) {
        return new \ReflectionClass($definition[$key]);
      }

      if (isset($definition[$key = $prefix . '_factory'])) {
        return CallbackUtil::callableGetReflector($definition[$key]);
      }
    }

    return NULL;
  }
}
