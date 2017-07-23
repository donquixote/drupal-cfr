<?php

namespace Drupal\cfrapi\FormatorP2;

use Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;

/**
 * @Cf
 */
class FormatorP2_ConfToForm implements D7FormatorP2Interface {

  /**
   * @var \Drupal\cfrapi\ConfToForm\ConfToFormInterface
   */
  private $confToForm;

  /**
   * @param \Drupal\cfrapi\ConfToForm\ConfToFormInterface $schema
   */
  public function __construct(ConfToFormInterface $schema) {
    $this->confToForm = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array|null
   * @internal param \Donquixote\Cf\Translator\TranslatorInterface $helper
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {
    return $this->confToForm->confGetForm($conf, $label);
  }
}
