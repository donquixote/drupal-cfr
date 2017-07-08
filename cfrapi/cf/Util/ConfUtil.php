<?php

namespace Donquixote\Cf\Util;

class ConfUtil extends UtilBase {

  /**
   * @param mixed $conf
   * @param string $k0
   * @param string $k1
   *
   * @return array
   *   Format: array($id, $options)
   */
  public static function confGetIdOptions($conf, $k0 = 'id', $k1 = 'options') {

    if (!is_array($conf)) {
      return [NULL, NULL];
    }

    if (!isset($conf[$k0])) {
      return [NULL, NULL];
    }

    if ('' === $id = $conf[$k0]) {
      return [NULL, NULL];
    }

    if (!is_string($id) && !is_int($id)) {
      return [NULL, NULL];
    }

    if (!isset($conf[$k1])) {
      return [$id, NULL];
    }

    return [$id, $conf[$k1]];
  }

  /**
   * @param mixed $conf
   * @param string $k0
   * @param string $k1
   *
   * @return array
   *   Format: [$enabled, $options]
   */
  public static function confGetStatusAndOptions($conf, $k0 = 'enabled', $k1 = 'options') {

    if (!is_array($conf) || empty($conf[$k0])) {
      return [FALSE, NULL];
    }

    if (!isset($conf[$k1])) {
      return [TRUE, NULL];
    }

    return [TRUE, $conf[$k1]];
  }

  /**
   * @param mixed $conf
   *
   * @return string|null
   */
  public static function confGetId($conf) {

    if (is_numeric($conf)) {
      return (string)$conf;
    }

    if (NULL === $conf || '' === $conf || !is_string($conf)) {
      return NULL;
    }

    return $conf;
  }

  /**
   * @param mixed $conf
   * @param string[] $parents
   *
   * @return mixed
   */
  public static function confExtractNestedValue(&$conf, array $parents) {
    if ([] === $parents) {
      return $conf;
    }
    if (!is_array($conf)) {
      return NULL;
    }
    $key = array_shift($parents);
    if (!isset($conf[$key])) {
      return NULL;
    }
    if ([] === $parents) {
      return $conf[$key];
    }
    if (!is_array($conf[$key])) {
      return NULL;
    }
    return self::confExtractNestedValue($conf[$key], $parents);
  }

  /**
   * @param mixed $conf
   * @param string[] $parents
   *
   * @return bool
   */
  public static function confUnsetNestedValue(&$conf, array $parents) {
    if ([] === $parents) {
      $conf = [];
      return TRUE;
    }
    if (!is_array($conf)) {
      return FALSE;
    }
    $key = array_shift($parents);
    if ([] === $parents) {
      unset($conf[$key]);
      return TRUE;
    }
    if (!isset($conf[$key])) {
      return TRUE;
    }
    return self::confUnsetNestedValue($conf[$key], $parents);
  }
}
