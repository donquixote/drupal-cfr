<?php

namespace Drupal\cfrapi\Util;

use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIA;
use Donquixote\Cf\Discovery\ClassFilesIA_NamespaceDirectory;
use Donquixote\Cf\Discovery\NamespaceDirectory;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Util\STAMappersUtil;

final class DrupalSTAUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function collectSTAPartials(ParamToValueInterface $paramToValue) {

    // Search all of \Drupal\cfrapi namespace.
    $drupalNsDir = NamespaceDirectory::createFromClass(self::class)
      ->parent();

    $drupalClassFilesIA = ClassFilesIA_NamespaceDirectory::createFromNsdirObject($drupalNsDir);

    $drupalFactoriesIA = new AnnotatedFactoriesIA(
      $drupalClassFilesIA,
      'Cf');

    return STAMappersUtil::collectSTAPartials(
      $drupalFactoriesIA,
      $paramToValue);
  }

}
