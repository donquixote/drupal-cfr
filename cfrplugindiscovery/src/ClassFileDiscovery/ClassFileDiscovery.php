<?php

namespace Drupal\cfrplugindiscovery\ClassFileDiscovery;

use Drupal\cfrplugindiscovery\Util\DiscoveryUtil;

class ClassFileDiscovery implements ClassFileDiscoveryInterface {

  /**
   * See http://php.net/manual/en/language.oop5.basic.php
   */
  const CLASS_NAME_REGEX = '/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(|\.php)$/';

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return string[]
   *   Format: $[$file] = $class
   */
  public function dirNspGetClassFiles($directory, $namespace) {
    DiscoveryUtil::normalizeDirectory($directory);
    DiscoveryUtil::normalizeNamespace($namespace);
    return $this->dirNspFindClassFilesRecursive($directory, $namespace);
  }

  /**
   * @param string $parentDir
   * @param $parentNamespace
   *
   * @return string[]
   *   Format: $[$file] = $class
   */
  private function dirNspFindClassFilesRecursive($parentDir, $parentNamespace) {

    if (!is_dir($parentDir)) {
      return [];
    }

    $classFiles = [];
    foreach (scandir($parentDir) as $candidate) {

      if ('.' === $candidate[0]) {
        // File or directory is hidden, or $candidate is '.' or '..'.
        continue;
      }

      $path = $parentDir . '/' . $candidate;

      if ('.php' === substr($candidate, -4)) {
        if (!is_file($path)) {
          continue;
        }
        $name = substr($candidate, 0, -4);
        if (!preg_match(self::CLASS_NAME_REGEX, $name)) {
          continue;
        }
        $class = $parentNamespace . $name;
        $classFiles[$path] = $class;
      }
      else {
        if (!is_dir($path)) {
          continue;
        }
        if (!preg_match(self::CLASS_NAME_REGEX, $candidate)) {
          continue;
        }
        $classFiles += $this->dirNspFindClassFilesRecursive($path, $parentNamespace . $candidate . '\\');
      }
    }

    return $classFiles;
  }

}
