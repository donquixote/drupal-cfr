<?php

namespace Drupal\cfrplugindiscovery\ClassFileDiscovery;

use Drupal\cfrplugindiscovery\Util\DiscoveryUtil;

class ClassFileDiscovery implements ClassFileDiscoveryInterface {

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
    $classFiles = [];
    foreach (scandir($parentDir) as $candidate) {
      if ('.' === $candidate[0]) {
        continue;
      }
      $path = $parentDir . '/' . $candidate;
      if ('.php' === substr($candidate, -4)) {
        $name = substr($candidate, 0, -4);
        $class = $parentNamespace . $name;
        $classFiles[$path] = $class;
      }
      else {
        $classFiles += $this->dirNspFindClassFilesRecursive($path, $parentNamespace . $candidate . '\\');
      }
    }
    return $classFiles;
  }

}
