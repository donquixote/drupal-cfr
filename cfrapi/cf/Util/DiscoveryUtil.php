<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory;
use Donquixote\Cf\Discovery\ClassFilesIAInterface;
use Donquixote\Cf\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\Cf\Translator\Translator;

final class DiscoveryUtil extends UtilBase {

  /**
   * @return \Donquixote\Cf\ParamToValue\ParamToValueInterface
   */
  public static function getParamToValue() {
    $services = [];
    $services[] = Translator::createPassthru();
    return new ParamToValue_ObjectsMatchType($services);
  }

  /**
   * @param \Donquixote\Cf\Discovery\ClassFilesIAInterface $classFilesIA
   *
   * @return \Iterator|\ReflectionClass[]
   *   Format: $[$file] = $class
   */
  public static function findReflectionClasses(ClassFilesIAInterface $classFilesIA) {

    $classFilesIA = $classFilesIA->withRealpathRoot();

    foreach ($classFilesIA as $file => $class) {

      if (!class_exists($class)) {
        continue;
      }

      $reflClass = new \ReflectionClass($class);

      if ($file !== $reflClass->getFileName()) {
        // It seems like this class is defined elsewhere.
        continue;
      }

      yield $reflClass;
    }
  }

  /**
   * @param \ReflectionClass $reflClass
   * @param string $annotationTagName
   *
   * @return \Iterator|\Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory[]
   */
  public static function classFindAnnotatedFactories(
    \ReflectionClass $reflClass,
    $annotationTagName
  ) {

    if (1
      && $reflClass->isInstantiable()
      && FALSE !== ($classDoc = $reflClass->getDocComment())
      && FALSE !== strpos($classDoc, '@' . $annotationTagName)
    ) {
      yield AnnotatedFactory::createFromClass($reflClass);
    }

    foreach ($reflClass->getMethods(\ReflectionMethod::IS_STATIC) as $reflectionMethod) {
      if (1
        && FALSE !== ($methodDoc = $reflectionMethod->getDocComment())
        && FALSE !== strpos($methodDoc, '@' . $annotationTagName)
      ) {
        yield AnnotatedFactory::createFromStaticMethod($reflectionMethod);
      }
    }
  }

}
