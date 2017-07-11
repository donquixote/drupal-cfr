<?php

namespace Donquixote\Cf\Translator\Lookup;

interface TranslatorLookupInterface {

  /**
   * @param string $string
   *
   * @return string
   */
  public function lookup($string);

}
