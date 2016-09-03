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
  static function create($tagName = 'CfrPlugin') {
    return new self(
      new ClassFileDiscovery(),
      ClassFileToDefinitions_NativeReflection::create($tagName));
  }

  /**
   * @param \Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface $classFileDiscovery
   * @param \Drupal\cfrplugindiscovery\ClassFileToDefinitions\ClassFileToDefinitionsInterface $classFileToDefinitions
   */
  function __construct(
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
   * @param string $directory
   * @param string $namespace
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  function discoverByInterface($directory, $namespace) {

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
