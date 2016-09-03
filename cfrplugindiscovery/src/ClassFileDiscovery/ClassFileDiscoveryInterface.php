<?php
namespace Drupal\cfrplugindiscovery\ClassFileDiscovery;

interface ClassFileDiscoveryInterface {

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return string[]
   *   Format: $[$file] = $class
   */
  public function dirNspGetClassFiles($directory, $namespace);
}
