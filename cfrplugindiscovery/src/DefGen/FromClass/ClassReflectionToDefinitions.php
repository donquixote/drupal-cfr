<?php

namespace Drupal\cfrplugindiscovery\DefGen\FromClass;

use Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface;
use Donquixote\HastyReflectionCommon\Util\ReflectionUtil;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface;
use Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypesInterface;
use Drupal\cfrplugindiscovery\Util\DefinitionUtil;

class ClassReflectionToDefinitions implements ClassReflectionToDefinitionsInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface
   */
  private $docToAnnotations;

  /**
   * @var \Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypesInterface
   */
  private $docToReturnTypes;

  /**
   * @param \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param \Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypesInterface $docToReturnTypes
   */
  function __construct(DocToAnnotationsInterface $docToAnnotations, DocToReturnTypesInterface $docToReturnTypes) {
    $this->docToAnnotations = $docToAnnotations;
    $this->docToReturnTypes = $docToReturnTypes;
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface $classLikeReflection
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  function classReflectionGetDefinitions(ClassLikeReflectionInterface $classLikeReflection) {

    $context = $classLikeReflection->getNamespaceUseContext();
    $docComment = $classLikeReflection->getDocComment();
    $annotations = $this->docToAnnotations->docGetAnnotations($docComment);
    if (!$annotations) {
      return array();
    }

    if ($classLikeReflection->extendsOrImplementsInterface(ConfiguratorInterface::class, TRUE)) {
      $stubDefinition = array(
        'configurator_class' => $classLikeReflection->getName(),
      );
      $confGetValueMethod = $classLikeReflection->getMethod('confGetValue');
      if (NULL === $confGetValueMethod) {
        return array();
      }
      $types = $this->docToReturnTypes->docGetReturnTypes($confGetValueMethod->getDocComment(), $context);
    }
    else {
      $stubDefinition = array(
        'handler_class' => $classLikeReflection->getName(),
      );
      $types = array();

      foreach (ReflectionUtil::classLikeGetFirstLevelInterfaces($classLikeReflection) as $interfaceQcn => $interfaceReflection) {
        $interfaceName = $interfaceReflection->getName();
        $types[$interfaceName] = $interfaceName;
      }
    }

    $className = $classLikeReflection->getShortName();

    $definitionsById = DefinitionUtil::buildDefinitionsById($stubDefinition, $annotations, $className);
    return DefinitionUtil::buildDefinitionsByTypeAndId($types, $definitionsById);
  }

}
