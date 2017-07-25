<?php

namespace Donquixote\Cf\Form\D7;

class FormatorD7_Bool implements FormatorD7Interface {

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function confGetD7Form($conf, $label) {

    return [
      '#type' => 'checkbox',
      '#label' => $label,
      '#default_value' => !empty($conf),
    ];
  }
}
