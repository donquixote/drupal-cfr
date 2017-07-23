<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;

class D7FormatorP2_Broken implements D7FormatorP2Interface {

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
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

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
