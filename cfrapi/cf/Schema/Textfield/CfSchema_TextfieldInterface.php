<?php

namespace Donquixote\Cf\Schema\Textfield;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface CfSchema_TextfieldInterface extends CfSchemaInterface {

  public function textIsValid($text);

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text);

}
