<?php

namespace Donquixote\Cf\Form\D7\Helper;

use Donquixote\Cf\Form\D7\D7FormatorInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

interface D7FormatorHelperInterface extends D7FormatorInterface, TranslatorInterface {

  /**
   * @param string $string
   * @param array $replacements
   *
   * @return string
   */
  public function translate($string, array $replacements = []);

}
