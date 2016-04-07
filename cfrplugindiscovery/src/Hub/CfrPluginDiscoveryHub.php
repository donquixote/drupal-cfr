<?php

namespace Drupal\cfrplugindiscovery\Hub;

use Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface;
use Donquixote\HastyReflectionParser\ClassIndex\ClassIndex_Ast;
use Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitions_InclStaticMethods;
use Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface;
use Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscovery;
use Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface;

class CfrPluginDiscoveryHub implements CfrPluginDiscoveryHubInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface
   */
  private $classFileDiscovery;

  /**
   * @var \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface
   */
  private $classToDefinitions;

  /**
   * @var \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface
   */
  private $classIndex;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @return \Drupal\cfrplugindiscovery\Hub\CfrPluginDiscoveryHub
   */
  static function create() {
    $tagName = 'CfrPlugin';
    $classIndex = ClassIndex_Ast::createSemiNative(TRUE);
    return new self(
      new ClassFileDiscovery(),
      $classIndex,
      ClassReflectionToDefinitions_InclStaticMethods::create($classIndex, $tagName),
      $tagName);
  }

  /**
   * @param \Drupal\cfrplugindiscovery\ClassFileDiscovery\ClassFileDiscoveryInterface $classFileDiscovery
   * @param \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface $classIndex
   * @param \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface $classToDefinitions
   * @param string $tagName
   */
  function __construct(
    ClassFileDiscoveryInterface $classFileDiscovery,
    ClassIndexInterface $classIndex,
    ClassReflectionToDefinitionsInterface $classToDefinitions,
    $tagName
  ) {
    $this->classFileDiscovery = $classFileDiscovery;
    $this->classToDefinitions = $classToDefinitions;
    $this->classIndex = $classIndex;
    $this->tagName = $tagName;
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

    $definitionsByTypeAndId = array();
    foreach ($classFiles as $file => $class) {
      $php = file_get_contents($file);
      if (FALSE === strpos($php, '@' . $this->tagName)) {
        continue;
      }
      $classLikeReflection = $this->classIndex->classGetReflection($class);
      if (NULL === $classLikeReflection) {
        continue;
      }
      if (!$classLikeReflection->isClass()) {
        // Skip interfaces and traits.
        continue;
      }
      foreach ($this->classToDefinitions->classReflectionGetDefinitions($classLikeReflection) as $type => $definitionsById) {
        foreach ($definitionsById as $id => $definition) {
          $definitionsByTypeAndId[$type][$id] = $definition;
        }
      }
    }
    return $definitionsByTypeAndId;
  }

}
