<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIA;
use Donquixote\Cf\Discovery\ClassFilesIA_NamespaceDirectory;
use Donquixote\Cf\Discovery\NamespaceDirectory;
use Donquixote\Cf\SchemaToAnything\SchemaToAnything_CallbackInstanceof;

final class LocalPackageUtil extends UtilBase {

  /**
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface[]
   */
  public static function collectSTAMappers() {

    $factoriesIA = self::getAnnotatedFactoriesIA('Cf');

    $mappers = [];
    foreach ($factoriesIA as $factory) {

      $candidate = SchemaToAnything_CallbackInstanceof::createFrom(
        $factory->getCallback()
      );

      if (NULL !== $candidate) {
        $mappers[] = $candidate;
      }
    }

    return $mappers;
  }

  public static function collectSTSMappers() {

    $factoriesIA = self::getAnnotatedFactoriesIA('Cf');

    $mappers = [];
    foreach ($factoriesIA as $factory) {



      $candidate = SchemaToAnything_CallbackInstanceof::createFrom(
        $factory->getCallback()
      );

      if (NULL !== $candidate) {
        $mappers[] = $candidate;
      }
    }

    return $mappers;
  }

  public static function getFactoriesByType() {

  }

  /**
   * @param $annotationTagName
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
      self::getNamespaceDir()
    );
  }

  /**
   * @return \Donquixote\Cf\Discovery\NamespaceDirectory
   */
  public static function getNamespaceDir() {
    return NamespaceDirectory::create(__DIR__, __NAMESPACE__)
      ->basedir();
  }
}
