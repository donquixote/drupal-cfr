<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Integration\D7\ConfToForm\ConfToD7FormInterface;

/**
 * @Cf
 */
class PartialD7Formator_ConfToD7Form implements PartialD7FormatorInterface {

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
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    return $this->schema->confGetForm($conf, $label);
  }
}
