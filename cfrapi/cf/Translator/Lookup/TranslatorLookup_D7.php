<?php

namespace Donquixote\Cf\Translator\Lookup;

class TranslatorLookup_D7 implements TranslatorLookupInterface {

  /**
   * @return \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface
   */
  public static function createOrPassthru() {

    if (NULL !== $lookup = self::create()) {
      return $lookup;
    }

    return new TranslatorLookup_Passthru();
  }

  /**
   * @return self|null
   */
  public static function create() {

    if (0
      || !function_exists('t')
      // Check some other functions as evidence for Drupal 7.
      || !function_exists('drupal_placeholder')
      || !function_exists('module_exists')
    ) {
      return NULL;
    }

    return new self();
  }

  /**
   * Access-restricted constructor, to make sure this is only created in Drupal
   * context.
   */
  protected function __construct() {}

  /**
   * @param string $string
   *
   * @return string
   */
  public function lookup($string) {
    // Calling t() without any replacements does exactly what we need.
    return t($string, [], []);
  }
}
