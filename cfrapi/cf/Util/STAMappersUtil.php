<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnything_CallbackInstanceof;

final class STAMappersUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   *
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface[]
   */
  public static function collectSTAMappers(AnnotatedFactoriesIAInterface $factoriesIA) {

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

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   *
   * @return \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface[]
   */
  public static function collectSTSMappers(AnnotatedFactoriesIAInterface $factoriesIA) {

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

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   */
  public static function getFactoriesByReturnType(AnnotatedFactoriesIAInterface $factoriesIA) {

    foreach ($factoriesIA as $factory) {
      $factory->getCallback();
    }
  }

}
