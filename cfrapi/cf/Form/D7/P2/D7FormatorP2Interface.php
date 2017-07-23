<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

interface D7FormatorP2Interface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array|null
   * @internal param \Donquixote\Cf\Translator\TranslatorInterface $helper
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator);

}
