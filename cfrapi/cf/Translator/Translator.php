<?php

namespace Donquixote\Cf\Translator;

use Donquixote\Cf\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface;
use Donquixote\Cf\Util\HtmlUtil;

class Translator implements TranslatorInterface {

  /**
   * @var \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface
   */
  private $lookup;

  /**
   * @return \Donquixote\Cf\Translator\Translator
   */
  public static function createPassthru() {
    return new self(new TranslatorLookup_Passthru());
  }

  /**
   * @param \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface|null $lookup
   *
   * @return \Donquixote\Cf\Translator\Translator
   */
  public static function create(TranslatorLookupInterface $lookup = NULL) {

    if (NULL === $lookup) {
      $lookup = new TranslatorLookup_Passthru();
    }

    return new self($lookup);
  }

  /**
   * @param \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface $lookup
   */
  public function __construct(TranslatorLookupInterface $lookup) {
    $this->lookup = $lookup;
  }

  /**
   * @param string $string
   * @param string[] $replacements
   *
   * @return string
   */
  public function translate($string, array $replacements = []) {

    $string = $this->lookup->lookup($string);
    $replacements = $this->processReplacements($replacements);

    return strtr($string, $replacements);
  }

  /**
   * @param string[] $replacements
   *
   * @return string[]
   */
  protected function processReplacements(array $replacements) {

    // Transform arguments before inserting them.
    foreach ($replacements as $key => $value) {
      switch ($key[0]) {
        case '@':
          // Escaped only.
          $replacements[$key] = HtmlUtil::sanitize($value);
          break;

        case '!':
          // Pass-through.
      }
    }

    return $replacements;
  }
}
