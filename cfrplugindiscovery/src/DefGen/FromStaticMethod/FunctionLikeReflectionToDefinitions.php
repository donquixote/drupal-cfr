<?php

namespace Drupal\cfrplugindiscovery\DefGen\FromStaticMethod;

use Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface;
use Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface;
use Donquixote\HastyReflectionCommon\Reflection\FunctionLike\MethodReflectionInterface;
use Donquixote\HastyReflectionCommon\Util\ReflectionUtil;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface;
use Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypesInterface;
use Drupal\cfrplugindiscovery\Util\DefinitionUtil;

class FunctionLikeReflectionToDefinitions implements FunctionLikeReflectionToDefinitionsInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface
   */
  private $docToAnnotations;

  /**
   * @var \Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypesInterface
   */
  private $docToReturnTypes;

  /**
   * @var \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface
   */
  private $classIndex;

  /**
   * @param \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param \Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypesInterface $docToReturnTypes
   * @param \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface $classIndex
   */
  function __construct(
    DocToAnnotationsInterface $docToAnnotations,
    DocToReturnTypesInterface $docToReturnTypes,
    ClassIndexInterface $classIndex
  ) {
    $this->docToAnnotations = $docToAnnotations;
    $this->docToReturnTypes = $docToReturnTypes;
    $this->classIndex = $classIndex;
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface $method
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  function functionReflectionGetDefinitions(FunctionLikeReflectionInterface $method) {

    $docComment = $method->getDocComment();

    $namespaceUseContext = $method->getNamespaceUseContext();

    $annotations = $this->docToAnnotations->docGetAnnotations($docComment, $namespaceUseContext);
    if (!$annotations) {
      return array();
    }

    $methodReturnTypeNames = $this->docToReturnTypes->docGetReturnTypes($docComment, $namespaceUseContext);

    foreach ($methodReturnTypeNames as $returnTypeName) {
      $typeReflection = $this->classIndex->classGetReflection($returnTypeName);
      if (NULL === $typeReflection) {
        continue;
      }
      if ($typeReflection->extendsOrImplementsInterface(ConfiguratorInterface::class, TRUE)) {

        // The method returns a configurator object.
        // The actual plugin type has to be determined elsewhere:
        // We simply assume that
        $definition = array(
          'configurator_factory' => $method->getQualifiedName(),
        );
        if (!$method instanceof MethodReflectionInterface) {
          return array();
        }
        $declaringClassReflection = $method->getDeclaringClass();
        $pluginTypeNames = array_keys(ReflectionUtil::classLikeGetFirstLevelInterfaces($declaringClassReflection));
        $definitionsById = DefinitionUtil::buildDefinitionsById($definition, $annotations, $method->getQualifiedName());
        return DefinitionUtil::buildDefinitionsByTypeAndId($pluginTypeNames, $definitionsById);
      }
    }

    $definition = array(
      'handler_factory' => $method->getQualifiedName(),
    );
    $definitionsById = DefinitionUtil::buildDefinitionsById($definition, $annotations, $method->getQualifiedName());
    return DefinitionUtil::buildDefinitionsByTypeAndId($methodReturnTypeNames, $definitionsById);
  }

}
