<?php

namespace Drupal\cfrplugindiscovery\ClassFileToDefinitions;

use Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface;
use Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface;
use Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface;
use Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface;
use Donquixote\HastyReflectionCommon\Reflection\FunctionLike\MethodReflectionInterface;
use Donquixote\HastyReflectionCommon\Util\ReflectionUtil;
use Donquixote\HastyReflectionParser\ClassIndex\ClassIndex_Ast;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotations;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface;
use Drupal\cfrplugindiscovery\DocToReturnTypesString\DocToReturnTypesString_phpDocumentor;
use Drupal\cfrplugindiscovery\DocToReturnTypesString\DocToReturnTypesStringInterface;
use Drupal\cfrplugindiscovery\Util\DefinitionUtil;

class ClassFileToDefinitions_HastyReflectionParser implements ClassFileToDefinitionsInterface {

  /**
   * @var \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface
   */
  private $classIndex;

  /**
   * @var \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface
   */
  private $docToAnnotations;

  /**
   * @var \Drupal\cfrplugindiscovery\DocToReturnTypesString\DocToReturnTypesStringInterface
   */
  private $docToReturnTypesString;

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
      DocToAnnotations::create($tagName),
      DocToReturnTypesString_phpDocumentor::create(),
      $tagName);
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Canvas\ClassIndex\ClassIndexInterface $classIndex
   * @param \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param \Drupal\cfrplugindiscovery\DocToReturnTypesString\DocToReturnTypesStringInterface $docToReturnTypeString
   * @param string $tagName
   *   E.g. 'CfrPlugin'.
   */
  public function __construct(
    ClassIndexInterface $classIndex,
    DocToAnnotationsInterface $docToAnnotations,
    DocToReturnTypesStringInterface $docToReturnTypeString,
    $tagName
  ) {
    $this->classIndex = $classIndex;
    $this->docToAnnotations = $docToAnnotations;
    $this->docToReturnTypesString = $docToReturnTypeString;
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

    return $this->classGetDefinitions($classLikeReflection);
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface $classLikeReflection
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function classGetDefinitions(ClassLikeReflectionInterface $classLikeReflection) {

    $definitionsByTypeAndId = [];

    if (!$classLikeReflection->isAbstract()) {
      $definitionsByTypeAndId = $this->classGetDefinitionsForClass($classLikeReflection);
    }

    foreach ($classLikeReflection->getMethods() as $methodReflection) {
      if (!$methodReflection->isStatic()) {
        continue;
      }
      if ($methodReflection->isAbstract()) {
        continue;
      }
      foreach ($this->methodGetDefinitions($methodReflection) as $type => $definitions) {
        foreach ($definitions as $id => $definition) {
          $definitionsByTypeAndId[$type][$id] = $definition;
        }
      }
    }

    return $definitionsByTypeAndId;
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface $classLikeReflection
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function classGetDefinitionsForClass(ClassLikeReflectionInterface $classLikeReflection) {

    $context = $classLikeReflection->getNamespaceUseContext();
    if (!$docComment = $classLikeReflection->getDocComment()) {
      return [];
    }

    if ([] === $annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    if ($classLikeReflection->extendsOrImplementsInterface(ConfiguratorInterface::class, TRUE)) {

      $stubDefinition = [
        'configurator_class' => $classLikeReflection->getName(),
      ];

      if (NULL === $confGetValueMethod = $classLikeReflection->getMethod('confGetValue')) {
        return [];
      }

      if ([] === $types = $this->docGetReturnTypes($confGetValueMethod->getDocComment(), $context)) {
        return [];
      }
    }
    else {
      $stubDefinition = [
        'handler_class' => $classLikeReflection->getName(),
      ];

      $types = [];
      foreach (ReflectionUtil::classLikeGetFirstLevelInterfaces($classLikeReflection) as $interfaceQcn => $interfaceReflection) {
        $interfaceName = $interfaceReflection->getName();
        $types[$interfaceName] = $interfaceName;
      }

      if ([] === $types) {
        return [];
      }
    }

    $className = $classLikeReflection->getShortName();

    $definitionsById = DefinitionUtil::buildDefinitionsById($stubDefinition, $annotations, $className);
    return DefinitionUtil::buildDefinitionsByTypeAndId($types, $definitionsById);
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface $method
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function methodGetDefinitions(FunctionLikeReflectionInterface $method) {

    if (!$docComment = $method->getDocComment()) {
      return [];
    }

    $namespaceUseContext = $method->getNamespaceUseContext();

    if ([] === $annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    if ([] === $methodReturnTypeNames = $this->docGetReturnTypes($docComment, $namespaceUseContext)) {
      return [];
    }

    foreach ($methodReturnTypeNames as $returnTypeName) {
      $returnTypeReflection = $this->classIndex->classGetReflection($returnTypeName);
      if (NULL === $returnTypeReflection) {
        continue;
      }
      if ($returnTypeReflection->extendsOrImplementsInterface(ConfiguratorInterface::class, TRUE)) {

        // The method returns a configurator object.
        // The actual plugin type has to be determined elsewhere:
        // We simply assume that
        return self::configuratorFactoryGetDefinitions($method, $annotations);
      }
    }

    $definition = [
      'handler_factory' => $method->getQualifiedName(),
    ];
    $definitionsById = DefinitionUtil::buildDefinitionsById($definition, $annotations, $method->getQualifiedName());
    return DefinitionUtil::buildDefinitionsByTypeAndId($methodReturnTypeNames, $definitionsById);
  }

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface $method
   * @param array[] $annotations
   *   E.g. [['id' => 'entityTitle', 'label' => 'Entity title'], ..]
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private static function configuratorFactoryGetDefinitions(FunctionLikeReflectionInterface $method, array $annotations) {
    $definition = [
      'configurator_factory' => $method->getQualifiedName(),
    ];
    if (!$method instanceof MethodReflectionInterface) {
      return [];
    }
    $declaringClassReflection = $method->getDeclaringClass();
    $pluginTypeNames = array_keys(ReflectionUtil::classLikeGetFirstLevelInterfaces($declaringClassReflection));
    $definitionsById = DefinitionUtil::buildDefinitionsById($definition, $annotations, $method->getQualifiedName());
    return DefinitionUtil::buildDefinitionsByTypeAndId($pluginTypeNames, $definitionsById);
  }

  /**
   * @param string $docComment
   * @param \Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface $context
   *
   * @return string[]
   *   Format: $[$qcn] = $qcn
   */
  private function docGetReturnTypes($docComment, NamespaceUseContextInterface $context) {

    if (NULL === $returnTypesString = $this->docToReturnTypesString->docGetReturnTypesString($docComment)) {
      return [];
    }

    $returnTypes = [];
    foreach (explode('|', $returnTypesString) as $typeNameOrAlias) {
      $name = $context->aliasGetName($typeNameOrAlias);
      $returnTypes[$name] = $name;
    }

    return $returnTypes;
  }
}
