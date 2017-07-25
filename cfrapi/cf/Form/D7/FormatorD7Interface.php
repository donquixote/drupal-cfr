<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\Common\FormatorCommonInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

interface FormatorD7Interface extends FormatorCommonInterface {

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
