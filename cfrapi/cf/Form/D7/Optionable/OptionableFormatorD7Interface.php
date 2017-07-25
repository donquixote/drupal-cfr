<?php

namespace Donquixote\Cf\Form\D7\Optionable;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;

interface OptionableFormatorD7Interface extends FormatorCommonInterface {

  /**
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function getOptionalFormator();

}
