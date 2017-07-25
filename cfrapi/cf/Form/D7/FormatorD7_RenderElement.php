<?php

namespace Donquixote\Cf\Form\D7;

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
   *
   * @return array
   */
  public function confGetD7Form($conf, $label) {

    return $this->element;
  }
}
