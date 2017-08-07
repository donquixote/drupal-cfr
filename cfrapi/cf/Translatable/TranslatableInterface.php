<?php

namespace Donquixote\Cf\Translatable;

interface TranslatableInterface {

  /**
   * @return string
   */
  public function getOriginalText();

  /**
   * @return string[]
   */
  public function getReplacements();

}
