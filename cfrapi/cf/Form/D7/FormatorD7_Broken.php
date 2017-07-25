<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Util\HtmlUtil;

class FormatorD7_Broken implements FormatorD7Interface {

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
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label) {

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
