<?php

namespace Donquixote\Cf\Translator\Lookup;

class TranslatorLookup_Passthru implements TranslatorLookupInterface {

  /**
   * @param string $string
   *
   * @return string
   */
  public function lookup($string) {
    return $string;
  }
}
