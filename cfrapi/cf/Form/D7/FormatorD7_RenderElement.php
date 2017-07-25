<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Translator\TranslatorInterface;

class FormatorD7_RenderElement implements FormatorD7Interface {

  /**
   * @var array
   */
  private $element;

  /**
   * @param array $element
   */
  public function __construct(array $element) {
    $this->element = $element;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

    return $this->element;
  }
}
