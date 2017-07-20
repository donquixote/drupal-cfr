<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;

class PartialD7Formator_RenderElement implements PartialD7FormatorInterface {

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
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    return $this->element;
  }
}
