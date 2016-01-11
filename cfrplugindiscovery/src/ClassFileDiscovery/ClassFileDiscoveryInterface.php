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
  function dirNspGetClassFiles($directory, $namespace);
}
