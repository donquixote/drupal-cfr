<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;

interface FormatorD7Interface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label);

}
