<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Util\HtmlUtil;

class PartialD7Formator_Broken implements PartialD7FormatorInterface {

  /**
   * @var string
   */
  private $message;

  /**
   * @param string $message
   */
  public function __construct($message) {
    $this->message = $message;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    $form = [];

    $form['content'] = [
      '#type' => 'container',
    ];

    $form['content']['messages'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['error', 'messages']],
    ];

    $form['content']['messages']['message'] = [
      '#markup' => HtmlUtil::sanitize($this->message),
    ];

    $form['#element_validate'][] = function(array $element) {
      form_error($element, "Broken configurator. The form will always fail to validate.");
    };

    return $form;
  }
}
