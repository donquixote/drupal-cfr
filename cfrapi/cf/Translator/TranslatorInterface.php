<?php

namespace Donquixote\Cf\Translator;

interface TranslatorInterface {

  /**
   * @param string $string
   * @param string[] $replacements
   *
   * @return string
   */
  public function translate($string, array $replacements = []);

}
