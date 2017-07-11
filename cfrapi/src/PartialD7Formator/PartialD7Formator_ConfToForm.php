<?php

namespace Drupal\cfrapi\PartialD7Formator;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;

/**
 * @todo This belongs into the Drupal module.
 */
class PartialD7Formator_ConfToForm implements PartialD7FormatorInterface {

  /**
   * @var \Drupal\cfrapi\ConfToForm\ConfToFormInterface
   */
  private $schema;

  /**
   * @param \Drupal\cfrapi\ConfToForm\ConfToFormInterface $schema
   */
  public function __construct(ConfToFormInterface $schema) {
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
