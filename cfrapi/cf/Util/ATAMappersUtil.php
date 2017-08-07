<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\ATA\ATA;
use Donquixote\Cf\ATA\Partial\ATAPartial_Callback;
use Donquixote\Cf\ATA\Partial\ATAPartialInterface;
use Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory;
use Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactoryInterface;
use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface;
use Donquixote\Cf\Markup\Markup;
use Donquixote\Cf\Markup\MarkupInterface;
use Donquixote\Cf\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Translatable\Translatable;
use Donquixote\Cf\Translator\Translator;

final class ATAMappersUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIAInterface $factoriesIA
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  public static function collectATAPartials(
    AnnotatedFactoriesIAInterface $factoriesIA,
    ParamToValueInterface $paramToValue
  ) {

    $atas = [];
    foreach ($factoriesIA as $factory) {

      foreach (self::factoryCreateATAs($factory, $paramToValue) as $ata) {
        $atas[] = $ata;
      }
    }

    return $atas;
  }

  /**
   * @param \Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactoryInterface $factory
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   * @param bool $goDeeper
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  public static function factoryCreateATAs(
    AnnotatedFactoryInterface $factory,
    ParamToValueInterface $paramToValue,
    $goDeeper = TRUE
  ) {

    $callback = $factory->getCallback();

    $candidate = ATAPartial_Callback::create(
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

    // @todo Stop here!
    if (!$goDeeper) {
      return [];
    }

    $candidate = ReflectionUtil::callbackInvokePTV(
      $callback,
      $paramToValue);

    if ($candidate instanceof ATAPartialInterface) {
      return [$candidate];
    }

    if (is_callable($candidate)) {
      /** @var callable $candidate */

      $factoryFromCallable = AnnotatedFactory::fromCallable($candidate);

      if (NULL === $factoryFromCallable) {
        return NULL;
      }

      return self::factoryCreateATAs(
        $factoryFromCallable,
        $paramToValue,
        FALSE);
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
   * @param \Donquixote\Cf\ATA\Partial\ATAPartialInterface[] $partials
   * @param string $sourceType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  public static function filterPartialsBySourceType(array $partials, $sourceType) {

    $filtered = [];
    foreach ($partials as $i => $partial) {
      if ($partial->acceptsSourceClass($sourceType)) {
        $filtered[$i] = $partial;
      }
    }

    return $filtered;
  }

  /**
   * @param \Donquixote\Cf\ATA\Partial\ATAPartialInterface[] $partials
   * @param string $targetType
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
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

  public static function test() {

    $objects = [];

    $objects[] = Translator::createPassthru();

    $ata = ATA::create(new ParamToValue_ObjectsMatchType($objects));

    $a = new Translatable(
      'Unknown !class in !file of !dir',
      [
        '!class' => 'C',
        '!file' => new Markup('F'),
        '!dir' => new Translatable('UUU @muh', ['@muh' => 'XX']),
      ]);

    $markup = $ata->cast($a, MarkupInterface::class);

    dpm($markup, __METHOD__);
  }

}
