<?php

namespace Donquixote\Cf\Form\D7\P2\Optionable;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;

interface OptionableD7FormatorP2Interface extends FormatorCommonInterface {

  /**
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public function getOptionalFormator();

}
