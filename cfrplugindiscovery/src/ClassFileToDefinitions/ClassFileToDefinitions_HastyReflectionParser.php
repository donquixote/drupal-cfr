<?php

namespace Drupal\cfrplugindiscovery\ClassFileToDefinitions;

use Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface;
use Donquixote\HastyReflectionParser\ClassIndex\ClassIndex_Ast;
use Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitions_InclStaticMethods;
use Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface;

class ClassFileToDefinitions_HastyReflectionParser implements ClassFileToDefinitionsInterface {

  /**
   * @var \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface
   */
  private $classIndex;

  /**
   * @var \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface
   */
  private $classToDefinitions;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @param string $tagName
   *
   * @return self
   */
  public static function create($tagName) {
    $classIndex = ClassIndex_Ast::createSemiNative(TRUE);
    return new self(
      $classIndex,
      ClassReflectionToDefinitions_InclStaticMethods::create(
        $classIndex,
        $tagName),
      $tagName);
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface $classIndex
   * @param \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface $classToDefinitions
   * @param string $tagName
   *   E.g. 'CfrPlugin'.
   */
  public function __construct(
    ClassIndexInterface $classIndex,
    ClassReflectionToDefinitionsInterface $classToDefinitions,
    $tagName
  ) {
    $this->classIndex = $classIndex;
    $this->classToDefinitions = $classToDefinitions;
    $this->tagName = $tagName;
  }

  /**
   * @param string $class
   * @param string $file
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  public function classFileGetDefinitions($class, $file) {

    if (FALSE === $php = file_get_contents($file)) {
      return [];
    }

    if (FALSE === strpos($php, '@' . $this->tagName)) {
      // File does not contain an annotation.
      return [];
    }

    if (NULL === $classLikeReflection = $this->classIndex->classGetReflection($class)) {
      return [];
    }

    if (!$classLikeReflection->isClass()) {
      // Skip interfaces and traits.
      return [];
    }

    return $this->classToDefinitions->classReflectionGetDefinitions($classLikeReflection);
  }
}
