<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_Callback;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;

final class STAMappersUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function collectSTAPartials(AnnotatedFactoriesIAInterface $factoriesIA) {

    $returnTypeNamesAll = [];
    $candidates = [];
    foreach ($factoriesIA as $factory) {

      $returnTypeNames = $factory->getReturnTypeNames();
      $returnTypeNamesAll += array_fill_keys($returnTypeNames, TRUE);
      # $returnTypeNames = array_filter($returnTypeNames, 'interface_exists');

      if ([] === $returnTypeNames) {
        $candidates[] = SchemaToAnythingPartial_Callback::create(
          $factory->getCallback());
      }
      else {

        foreach ($returnTypeNames as $returnTypeName) {
          if (is_a($returnTypeName, SchemaToAnythingPartialInterface::class, TRUE)) {
            if ([] === $factory->getCallback()->getReflectionParameters()) {
              $candidate = $factory->getCallback()->invokeArgs([]);
              if ($candidate instanceof SchemaToAnythingPartialInterface) {
                $candidates[] = $candidate;
              }
            }
            continue 2;
          }
        }

        foreach ($returnTypeNames as $returnTypeName) {
          $candidates[] = SchemaToAnythingPartial_Callback::create(
            $factory->getCallback(),
            $returnTypeName);
        }
      }
    }

    return array_filter($candidates);
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
