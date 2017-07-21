<?php

namespace Donquixote\Cf\Form\D7\Helper;

use Donquixote\Cf\Form\D7\D7FormatorInterface;

interface D7FormatorHelperInterface extends D7FormatorInterface {

  /**
   * @param string $string
   * @param array $replacements
   *
   * @return string
   */
  public function translate($string, array $replacements = []);

}
