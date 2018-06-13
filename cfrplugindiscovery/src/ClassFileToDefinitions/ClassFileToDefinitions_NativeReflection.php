<?php

namespace Drupal\cfrplugindiscovery\ClassFileToDefinitions;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotations;
use Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface;
use Drupal\cfrplugindiscovery\Util\DefinitionUtil;
use Drupal\cfrplugindiscovery\Util\DocUtil;

class ClassFileToDefinitions_NativeReflection implements ClassFileToDefinitionsInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface
   */
  private $docToAnnotations;

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
    return new self(
      DocToAnnotations::create($tagName),
      $tagName);
  }

  /**
   * @param \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotationsInterface $docToAnnotations
   * @param string $tagName
   */
  public function __construct(DocToAnnotationsInterface $docToAnnotations, $tagName) {
    $this->docToAnnotations = $docToAnnotations;
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
      return [];
    }

    try {
      $reflectionClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      // @todo Log this?
      return [];
    }

    if ($reflectionClass->isInterface() || $reflectionClass->isTrait()) {
      return [];
    }

    // Cause an error if the class is defined elsewhere.
    require_once $file;

    return $this->reflectionClassGetDefinitions($reflectionClass);
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function reflectionClassGetDefinitions(\ReflectionClass $reflectionClass) {

    $definitionsByTypeAndId = [];

    if (!$reflectionClass->isAbstract()) {
      $definitionsByTypeAndId = $this->reflectionClassGetDefinitionsForClass($reflectionClass);
    }

    foreach ($reflectionClass->getMethods() as $methodReflection) {

      if (0
        || !$methodReflection->isStatic()
        || $methodReflection->isAbstract()
        || $methodReflection->isConstructor()
      ) {
        continue;
      }

      foreach ($this->staticMethodGetDefinitions($methodReflection) as $type => $definitions) {
        foreach ($definitions as $id => $definition) {
          $definitionsByTypeAndId[$type][$id] = $definition;
        }
      }
    }

    return $definitionsByTypeAndId;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function reflectionClassGetDefinitionsForClass(\ReflectionClass $reflectionClass) {

    if (FALSE === $docComment = $reflectionClass->getDocComment()) {
      return [];
    }

    if ([] ===  $annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    if ($reflectionClass->implementsInterface(ConfiguratorInterface::class)) {
      $stubDefinition = ['configurator_class' => $reflectionClass->getName()];

      if (NULL === $confGetValueMethod = $reflectionClass->getMethod('confGetValue')) {
        return [];
      }

      $pluginTypeNames = $this->reflectionMethodGetReturnTypeNames($confGetValueMethod);
    }
    else {
      $stubDefinition = ['handler_class' => $reflectionClass->getName()];

      $pluginTypeNames = self::classGetPluginTypeNames($reflectionClass);
    }

    if ([] === $pluginTypeNames) {
      return [];
    }

    $className = $reflectionClass->getShortName();

    $definitionsById = DefinitionUtil::buildDefinitionsById($stubDefinition, $annotations, $className);
    return DefinitionUtil::buildDefinitionsByTypeAndId($pluginTypeNames, $definitionsById);
  }

  /**
   * @param \ReflectionMethod $method
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private function staticMethodGetDefinitions(\ReflectionMethod $method) {

    if (FALSE === $docComment = $method->getDocComment()) {
      return [];
    }

    if (!$annotations = $this->docToAnnotations->docGetAnnotations($docComment)) {
      return [];
    }

    if ([] === $returnTypeNames = $this->reflectionMethodGetReturnTypeNames($method)) {
      return [];
    }

    foreach ($returnTypeNames as $returnTypeName) {
      if (is_a($returnTypeName, ConfiguratorInterface::class, TRUE)) {
        // The method returns a configurator object.
        // The actual plugin type has to be determined elsewhere:
        // We simply assume that
        return self::configuratorFactoryGetDefinitions($method, $annotations);
      }
    }

    $name = $method->getDeclaringClass()->getName() . '::' . $method->getName();

    $definition = [
      'handler_factory' => $name,
    ];

    $definitionsById = DefinitionUtil::buildDefinitionsById($definition, $annotations, $name);
    return DefinitionUtil::buildDefinitionsByTypeAndId($returnTypeNames, $definitionsById);
  }

  /**
   * @param \ReflectionMethod $method
   * @param array[] $annotations
   *   E.g. [['id' => 'entityTitle', 'label' => 'Entity title'], ..]
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  private static function configuratorFactoryGetDefinitions(\ReflectionMethod $method, array $annotations) {

    $name = $method->getDeclaringClass()->getName() . '::' . $method->getName();

    $definition = [
      'configurator_factory' => $name,
    ];

    $pluginTypeNames = self::classGetPluginTypeNames($method->getDeclaringClass());
    $definitionsById = DefinitionUtil::buildDefinitionsById($definition, $annotations, $name);
    return DefinitionUtil::buildDefinitionsByTypeAndId($pluginTypeNames, $definitionsById);
  }

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return string[]
   *   Format: $[$interface] = $interface
   */
  private function reflectionMethodGetReturnTypeNames(\ReflectionMethod $reflectionMethod) {

    if (FALSE === $docComment = $reflectionMethod->getDocComment()) {
      return [];
    }

    if ([] === $returnTypeClassNames = DocUtil::docGetReturnTypeClassNames($docComment, $reflectionMethod->getDeclaringClass()->getName())) {
      return [];
    }

    $returnTypeInterfaceNames = [];
    foreach ($returnTypeClassNames as $returnTypeClassName) {
      try {
        $reflClass = new \ReflectionClass($returnTypeClassName);
      }
      catch (\ReflectionException $e) {
        continue;
      }
      if ($reflClass->isInterface()) {
        $returnTypeInterfaceNames[] = $returnTypeClassName;
      }
      elseif (!$reflClass->isTrait()) {
        foreach (self::classGetPluginTypeNames($reflClass) as $interfaceName) {
          $returnTypeInterfaceNames[] = $interfaceName;
        }
      }
    }

    return array_unique($returnTypeInterfaceNames);
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return string[]
   *   Format: $[] = $interface
   */
  private static function classGetPluginTypeNames(\ReflectionClass $reflectionClass) {

    if ($reflectionClass->isInterface()) {
      return [$reflectionClass->getName()];
    }

    $interfaces = $reflectionClass->getInterfaces();
    foreach ($interfaces as $interfaceName => $reflectionInterface) {
      if (!isset($interfaces[$interfaceName])) {
        continue;
      }
      foreach ($reflectionInterface->getInterfaceNames() as $nameToUnset) {
        unset($interfaces[$nameToUnset]);
      }
    }

    return array_keys($interfaces);
  }
}
