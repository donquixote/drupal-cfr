<?php

namespace Drupal\cfrapi\Util;

final class ConfUtil extends UtilBase {

  /**
   * @param mixed $conf
   * @param string $k0
   * @param string $k1
   *
   * @return array
   *   Format: array($id, $options)
   */
  public static function confGetIdOptions($conf, $k0 = 'id', $k1 = 'options') {
    if (!isset($conf[$k0])) {
      return [NULL, NULL];
    }
    $id = $conf[$k0];
    if ('' === $id) {
      return [NULL, NULL];
    }
    if (!\is_string($id) && !\is_int($id)) {
      return [NULL, NULL];
    }
    if (!isset($conf[$k1])) {
      return [$id, NULL];
    }

    return [$id, $conf[$k1]];
  }

  /**
   * @param mixed $conf
   * @param string[] $keys
   *
   * @return mixed[]
   */
  public static function confExtractOptions($conf, array $keys) {
    if (!\is_array($conf) || empty($conf)) {
      return array_fill(0, \count($keys), NULL);
    }
    $return = [];
    foreach ($keys as $k) {
      $return[] = isset($conf[$k]) ? $conf[$k] : NULL;
    }
    return $return;
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
    if (!\is_array($conf)) {
      return NULL;
    }
    $key = array_shift($parents);
    if (!isset($conf[$key])) {
      return NULL;
    }
    if ([] === $parents) {
      return $conf[$key];
    }
    if (!\is_array($conf[$key])) {
      return NULL;
    }
    return self::confExtractNestedValue($conf[$key], $parents);
  }

  /**
   * @param array $conf
   * @param string[] $parents
   *   Trail of keys indicating an array position within $conf.
   * @param array $value
   *
   * @return bool
   *   TRUE on success, FALSE on failure.
   */
  public static function confMergeNestedValue(array &$conf, array $parents, array $value) {
    if ([] === $parents) {
      $conf += $value;
      return TRUE;
    }
    $key = array_shift($parents);
    if (!isset($conf[$key])) {
      $conf[$key] = [];
    }
    elseif (!\is_array($conf[$key])) {
      return FALSE;
    }
    if ([] === $parents) {
      $conf[$key] += $value;
      return TRUE;
    }
    return self::confMergeNestedValue($conf[$key], $parents, $value);
  }

  /**
   * @param mixed $conf
   * @param string[] $parents
   *   Trail of keys indicating an array position within $conf.
   * @param mixed $value
   *
   * @return bool
   *   TRUE on success, FALSE on failure.
   */
  public static function confSetNestedValue(&$conf, array $parents, $value) {
    if ([] === $parents) {
      $conf = $value;
      return TRUE;
    }
    if (!\is_array($conf)) {
      return FALSE;
    }
    $key = array_shift($parents);
    if ([] === $parents) {
      $conf[$key] = $value;
      return TRUE;
    }
    if (!isset($conf[$key])) {
      $conf[$key] = [];
    }
    return self::confSetNestedValue($conf[$key], $parents, $value);
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
    if (!\is_array($conf)) {
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
