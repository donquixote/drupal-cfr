<?php

namespace Donquixote\Cf\Optionlessness;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;
use Donquixote\Cf\SchemaBase\CfSchemaBaseInterface;

interface OptionlessnessInterface extends FormatorCommonInterface, CfSchemaBaseInterface {

  /**
   * @return bool
   */
  public function isOptionless();

}
