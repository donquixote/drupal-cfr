<?php

namespace Drupal\cfrplugindiscovery\Hub;

use Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscovery;
use Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface;
use Drupal\cfrplugindiscovery\ClassFileToDefinitions\ClassFileToDefinitions_NativeReflection;
use Drupal\cfrplugindiscovery\ClassFileToDefinitions\ClassFileToDefinitionsInterface;

class CfrPluginDiscoveryHub implements CfrPluginDiscoveryHubInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface
   */
  private $classFileDiscovery;

  /**
   * @var \Drupal\cfrplugindiscovery\ClassFileToDefinitions\ClassFileToDefinitionsInterface
   */
  private $classFileToDefinitions;

  /**
   * @var string[]
   */
  private $classesToExclude = [];

  /**
   * @param string $tagName
   *
   * @return static
   */
  public static function create($tagName = 'CfrPlugin') {
    return new self(
      new ClassFileDiscovery(),
      ClassFileToDefinitions_NativeReflection::create($tagName));
  }

  /**
   * @param \Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface $classFileDiscovery
   * @param \Drupal\cfrplugindiscovery\ClassFileToDefinitions\ClassFileToDefinitionsInterface $classFileToDefinitions
   */
  public function __construct(
    ClassFileDiscoveryInterface $classFileDiscovery,
    ClassFileToDefinitionsInterface $classFileToDefinitions
  ) {
    $this->classFileDiscovery = $classFileDiscovery;
    $this->classFileToDefinitions = $classFileToDefinitions;
  }

  /**
   * @param string $classToExclude
   *
   * @return static
   */
  public function withoutClass($classToExclude) {
    $clone = clone $this;
    $clone->classesToExclude[$classToExclude] = $classToExclude;
    return $clone;
  }

  /**
   * @param string[] $classesToExclude
   *
   * @return static
   */
  public function withoutClasses(array $classesToExclude) {
    $clone = clone $this;
    foreach ($classesToExclude as $classToExclude) {
      $clone->classesToExclude[$classToExclude] = $classToExclude;
    }
    return $clone;
  }

  /**
   * Version of discoverByInterface() that does not require specifying the
   * module name.
   *
   * @param $__FILE__
   *   Path to a *.module file.
   *   Since this is usually called from such a file, modules will usually pass
   *   __FILE__.
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  public function moduleFileScanPsr4($__FILE__) {

    if (!preg_match('@/([A-Za-z_][A-Za-z0-9_]*)\.module$@', $__FILE__, $m)) {
      throw new \InvalidArgumentException("Not a module file path: '$__FILE__'.");
    }

    $module = $m[1];

    return $this->discoverByInterface(dirname($__FILE__) . '/src', 'Drupal\\' . $module);
  }

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  public function discoverByInterface($directory, $namespace) {

    $classFiles = $this->classFileDiscovery->dirNspGetClassFiles($directory, $namespace);

    if ([] !== $this->classesToExclude) {
      // This does preserve keys.
      $classFiles = array_diff($classFiles, $this->classesToExclude);
    }

    return $this->classFilesGetDefinitions($classFiles);
  }

  /**
   * @param string[] $classFiles
   *   Format: $[$file] = $class
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function classFilesGetDefinitions(array $classFiles) {

    $definitionsByTypeAndId = [];
    foreach ($classFiles as $file => $class) {
      foreach ($this->classFileToDefinitions->classFileGetDefinitions($class, $file) as $type => $definitionsById) {
        foreach ($definitionsById as $id => $definition) {
          $definitionsByTypeAndId[$type][$id] = $definition;
        }
      }
    }

    return $definitionsByTypeAndId;
  }

}
