<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactory;
use Donquixote\Cf\Discovery\ClassFilesIAInterface;

final class DiscoveryUtil extends UtilBase {

  public static function test() {

    $reflFunction = new \ReflectionMethod(self::class, 'test');

    $results = [];
    $results[] = ReflectionUtil::functionGetReturnTypeNames($reflFunction);

    /*
    $results[] = scandir(__DIR__);
    $results[] = class_exists(PhpHelperBase::class);
    $results[] = __NAMESPACE__;
    $results[] = __DIR__;
    $results[] = LocalPackageUtil::collectSTAMappers();
    $results[] = $sta = SchemaToAnything_Chain::create();
    $results[] = $sta->schema(
      new ValueProvider_FixedValue('x'),
      EvaluatorInterface::class);
    */

    \Drupal\krumong\dpm($results);
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
   * @return \Iterator|\Donquixote\Cf\Discovery\AnnotatedFactory[]
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
