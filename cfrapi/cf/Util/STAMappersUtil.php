<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory;
use Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactoryInterface;
use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_Callback;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;

final class STAMappersUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function collectSTAPartials(
    AnnotatedFactoriesIAInterface $factoriesIA,
    ParamToValueInterface $paramToValue
  ) {

    $stas = [];
    foreach ($factoriesIA as $factory) {

      foreach (self::factoryCreateSTAs($factory, $paramToValue) as $sta) {
        $stas[] = $sta;
      }
    }

    return $stas;
  }

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactoryInterface $factory
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   * @param bool $goDeeper
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function factoryCreateSTAs(
    AnnotatedFactoryInterface $factory,
    ParamToValueInterface $paramToValue,
    $goDeeper = TRUE
  ) {

    $callback = $factory->getCallback();

    $candidate = SchemaToAnythingPartial_Callback::create(
      $callback,
      $paramToValue);

    if (NULL !== $candidate) {
      $returnTypeNames = $factory->getReturnTypeNames();

      if ([] === $returnTypeNames) {

        return [$candidate];
      }
      else {
        $candidates = [];
        foreach ($returnTypeNames as $returnTypeName) {
          $candidates[] = $candidate->withResultType($returnTypeName);
        }

        return $candidates;
      }
    }

    if (!$goDeeper) {
      return [];
    }

    $candidate = ReflectionUtil::callbackInvokePTV(
      $callback,
      $paramToValue);

    if ($candidate instanceof SchemaToAnythingPartialInterface) {
      return [$candidate];
    }

    if (is_callable($candidate)) {
      /** @var callable $candidate */

      $factory = AnnotatedFactory::fromCallable($candidate);

      if (NULL === $factory) {
        return NULL;
      }

      return self::factoryCreateSTAs($factory, $paramToValue, FALSE);
    }

    return [];
  }

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   */
  public static function getFactoriesByReturnType(AnnotatedFactoriesIAInterface $factoriesIA) {

    foreach ($factoriesIA as $factory) {
      $factory->getCallback();
    }
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   * @param string $schemaType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function filterPartialsBySchemaType(array $partials, $schemaType) {

    $filtered = [];
    foreach ($partials as $i => $partial) {
      if ($partial->acceptsSchemaClass($schemaType)) {
        $filtered[$i] = $partial;
      }
    }

    return $filtered;
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  public static function filterPartialsByTargetType(array $partials, $targetType) {

    $filtered = [];
    foreach ($partials as $i => $partial) {
      if ($partial->providesResultType($targetType)) {
        $filtered[$i] = $partial;
      }
    }

    return $filtered;
  }

}
