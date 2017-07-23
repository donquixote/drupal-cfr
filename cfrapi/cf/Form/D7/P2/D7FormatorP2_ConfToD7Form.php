<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Integration\D7\ConfToForm\ConfToD7FormInterface;

/**
 * @Cf
 */
class D7FormatorP2_ConfToD7Form implements D7FormatorP2Interface {

  /**
   * @var \Donquixote\Cf\Integration\D7\ConfToForm\ConfToD7FormInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Integration\D7\ConfToForm\ConfToD7FormInterface $schema
   */
  public function __construct(ConfToD7FormInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

    return $this->schema->confGetForm($conf, $label);
  }
}
