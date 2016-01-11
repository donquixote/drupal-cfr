<?php

namespace Drupal\cfrplugindiscovery\Util;

final class DiscoveryUtil extends UtilBase {

  /**
   * @param string $directory
   */
  static function normalizeDirectory(&$directory) {
    $path_lastchar = substr($directory, -1);
    if ('/' === $path_lastchar || '\\' === $path_lastchar) {
      throw new \InvalidArgumentException('Path must be provided without trailing slash or backslash.');
    }
    if (!is_dir($directory)) {
      throw new \InvalidArgumentException('Not a directory: ' . check_plain($directory));
    }
  }

  /**
   * @param string $namespace
   */
  static function normalizeNamespace(&$namespace) {
    if ('\\' === substr($namespace, -1)) {
      throw new \InvalidArgumentException('Namespace must be provided without trailing backslash.');
    }
    if (!empty($namespace) && '\\' === $namespace[0]) {
      throw new \InvalidArgumentException('Namespace must be provided without preceding backslash.');
    }
    if ('' !== $namespace) {
      $namespace .= '\\';
    }
  }
}
