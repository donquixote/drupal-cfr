<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Translator\TranslatorInterface;

class D7FormatorP2_Bool implements D7FormatorP2Interface {

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

    return [
      '#type' => 'checkbox',
      '#label' => $label,
      '#default_value' => !empty($conf),
    ];
  }
}
