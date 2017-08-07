<?php

namespace Donquixote\Cf\Schema\Textfield;

abstract class CfSchema_TextfieldBase implements CfSchema_TextfieldInterface {

  /**
   * @param string $text
   *
   * @return bool
   */
  public function textIsValid($text) {
    return [] !== $this->textGetValidationErrors($text);
  }
}
