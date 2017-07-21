<?php

namespace Donquixote\Cf\Util;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIA;
use Donquixote\Cf\Discovery\ClassFilesIA_NamespaceDirectory;
use Donquixote\Cf\Discovery\NamespaceDirectory;
use Donquixote\Cf\SchemaToAnything\SchemaToAnything_CallbackInstanceof;
use Drupal\cfrplugin\Util\UiCodeUtil;

final class LocalPackageUtil extends UtilBase {

  /**
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface[][]
   */
  public static function collectSTAMappersGrouped() {

    $grouped = [];
    foreach (self::collectSTAMappers() as $mapper) {
      if ($mapper instanceof SchemaToAnything_CallbackInstanceof) {
        $k = $mapper->getSchemaInterface();
      }
      else {
        $k = '?';
      }
      $grouped[$k][] = $mapper;
    }

    return $grouped;
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function collectSTAPartials() {

    $factoriesIA = self::getAnnotatedFactoriesIA('Cf');
    return STAMappersUtil::collectSTAPartials($factoriesIA);
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface[]
   */
  public static function collectSTAMappers() {

    $factoriesIA = self::getAnnotatedFactoriesIA('Cf');
    return STAMappersUtil::collectSTAMappers($factoriesIA);
  }

  /**
   * @return \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface[]
   */
  public static function collectSTSMappers() {

    $factoriesIA = self::getAnnotatedFactoriesIA('Cf');
    return STAMappersUtil::collectSTSMappers($factoriesIA);
  }

  /**
   * @param string $annotationTagName
   *
   * @return string
   */
  public static function showFactoriesPhpNice($annotationTagName) {
    $php = self::showFactoriesPhp($annotationTagName);
    return UiCodeUtil::highlightPhp($php);
  }

  /**
   * @param string $annotationTagName
   *
   * @return string
   */
  public static function showFactoriesPhp($annotationTagName) {

    $argsPhp = ['$schema'];
    $helper = new CodegenHelper();

    $itemsPhp = [];
    foreach (self::getAnnotatedFactoriesIA($annotationTagName) as $factory) {
      $itemsPhp[] = $factory->getCallback()->argsPhpGetPhp($argsPhp, $helper);
    }

    $php = PhpUtil::phpArray($itemsPhp);

    return PhpUtil::formatAsFile('return ' . $php);
  }

  /**
   * @param string $annotationTagName
   *
   * @return \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface
   */
  public static function getAnnotatedFactoriesIA($annotationTagName) {
    return new AnnotatedFactoriesIA(
      self::getClassFilesIA(),
      $annotationTagName);
  }

  /**
   * @return \Donquixote\Cf\Discovery\ClassFilesIAInterface
   */
  public static function getClassFilesIA() {
    return ClassFilesIA_NamespaceDirectory::createFromNsdirObject(
      self::getNamespaceDir());
  }

  /**
   * @return \Donquixote\Cf\Discovery\NamespaceDirectory
   */
  public static function getNamespaceDir() {
    return NamespaceDirectory::create(__DIR__, __NAMESPACE__)
      ->basedir();
  }
}
