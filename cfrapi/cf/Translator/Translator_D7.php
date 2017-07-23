<?php

namespace Donquixote\Cf\Translator;

use Donquixote\Cf\Translator\Lookup\TranslatorLookup_D7;
use Donquixote\Cf\Util\UtilBase;

final class Translator_D7 extends UtilBase {

  /**
   * @return \Donquixote\Cf\Translator\TranslatorInterface
   */
  public static function createOrPassthru() {
    return new Translator(TranslatorLookup_D7::createOrPassthru());
  }

  /**
   * @return \Donquixote\Cf\Translator\TranslatorInterface|null
   */
  public static function create() {

    if (NULL === $lookup = TranslatorLookup_D7::create()) {
      return NULL;
    }

    return new Translator($lookup);
  }



}
