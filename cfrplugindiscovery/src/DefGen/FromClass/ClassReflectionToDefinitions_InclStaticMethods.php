<?php

namespace Drupal\cfrplugindiscovery\DefGen\FromClass;

use Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface;
use Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface;
use Drupal\cfrplugindiscovery\DefGen\FromStaticMethod\FunctionLikeReflectionToDefinitions;
use Drupal\cfrplugindiscovery\DefGen\FromStaticMethod\FunctionLikeReflectionToDefinitionsInterface;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotations;
use Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypes_phpDocumentor;

class ClassReflectionToDefinitions_InclStaticMethods implements ClassReflectionToDefinitionsInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface
   */
  private $classreflToDefinitions;

  /**
   * @var \Drupal\cfrplugindiscovery\DefGen\FromStaticMethod\FunctionLikeReflectionToDefinitionsInterface
   */
  private $methodReflToDefinitions;

  /**
   * @param \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface $classIndex
   * @param string $tagName
   *
   * @return \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitions_InclStaticMethods
   */
  static function create(ClassIndexInterface $classIndex, $tagName) {
    $docToAnnotations = DocToAnnotations::create($tagName);
    $docToReturnTypes = DocToReturnTypes_phpDocumentor::create();
    return new self(
      new ClassReflectionToDefinitions($docToAnnotations, $docToReturnTypes),
      new FunctionLikeReflectionToDefinitions($docToAnnotations, $docToReturnTypes, $classIndex));
  }

  /**
   * @param \Drupal\cfrplugindiscovery\DefGen\FromClass\ClassReflectionToDefinitionsInterface $classreflToDefinitions
   * @param \Drupal\cfrplugindiscovery\DefGen\FromStaticMethod\FunctionLikeReflectionToDefinitionsInterface $methodReflToDefinitions
   */
  function __construct(
    ClassReflectionToDefinitionsInterface $classreflToDefinitions,
    FunctionLikeReflectionToDefinitionsInterface $methodReflToDefinitions
  ) {
    $this->classreflToDefinitions = $classreflToDefinitions;
    $this->methodReflToDefinitions = $methodReflToDefinitions;
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface $classLikeReflection
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  function classReflectionGetDefinitions(ClassLikeReflectionInterface $classLikeReflection) {
    $definitionsByTypeAndId = array();
    if (!$classLikeReflection->isAbstract()) {
      $definitionsByTypeAndId = $this->classreflToDefinitions->classReflectionGetDefinitions($classLikeReflection);
    }
    foreach ($classLikeReflection->getMethods() as $methodReflection) {
      if (!$methodReflection->isStatic()) {
        continue;
      }
      if ($methodReflection->isAbstract()) {
        continue;
      }
      foreach ($this->methodReflToDefinitions->functionReflectionGetDefinitions($methodReflection) as $type => $definitions) {
        foreach ($definitions as $id => $definition) {
          $definitionsByTypeAndId[$type][$id] = $definition;
        }
      }
    }
    return $definitionsByTypeAndId;
  }

}
