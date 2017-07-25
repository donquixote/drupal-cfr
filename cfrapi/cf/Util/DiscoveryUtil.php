<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactory;
use Donquixote\Cf\Discovery\ClassFilesIAInterface;
use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Schema\Options\CfSchema_Options_Fixed;
use Donquixote\Cf\Translator\Translator;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\cfrplugin\Util\UiDumpUtil;

final class DiscoveryUtil extends UtilBase {

  public static function test_2() {
    $container = CfrPluginHub::getContainer();
    $sta = $container->schemaToAnything;
    $schema = CfSchema_Options_Fixed::createFlat(
      [
        'a' => 'A',
        'b' => 'B',
      ]);
    $emptyness = $sta->schema($schema, EmptinessInterface::class);
    dpm($emptyness);
  }

  public static function test_0() {
    $services = [];
    $services[] = Translator::createPassthru();
    $paramToValue = new ParamToValue_ObjectsMatchType($services);
    $partials = LocalPackageUtil::collectSTAPartials($paramToValue);
    $filtered = STAMappersUtil::filterPartialsBySchemaType($partials, CfSchema_OptionalInterface::class);
    $ff = STAMappersUtil::filterPartialsByTargetType($filtered, EvaluatorInterface::class);
    kdpm($filtered);
    kdpm($ff);
  }

  public static function test_1() {
    $e = new \Exception();
    UiDumpUtil::displayException($e);
  }

  public static function test() {

    $paramToValue = self::getParamToValue();

    $results = LocalPackageUtil::collectSTAPartials($paramToValue);

    kdpm($results);
  }

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
