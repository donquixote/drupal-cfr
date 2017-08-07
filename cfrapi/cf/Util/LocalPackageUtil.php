<?php

namespace Donquixote\Cf\Util;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIA;
use Donquixote\Cf\Discovery\ClassFilesIA_NamespaceDirectory;
use Donquixote\Cf\Discovery\NamespaceDirectory;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;

final class LocalPackageUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  public static function collectATAPartials(ParamToValueInterface $paramToValue) {

    $factoriesIA = self::getAnnotatedFactoriesIA('ATA');

    return ATAMappersUtil::collectATAPartials(
      $factoriesIA,
      $paramToValue);
  }

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function collectSTAPartials(ParamToValueInterface $paramToValue) {

    $factoriesIA = self::getAnnotatedFactoriesIA('Cf');
    return STAMappersUtil::collectSTAPartials(
      $factoriesIA,
      $paramToValue);
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
