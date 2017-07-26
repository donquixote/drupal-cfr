<?php

namespace Drupal\cfrapi\Formator;

use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_CallbackNoHelper;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;
use Donquixote\Cf\Form\D7\FormatorD7Interface;

class FormatorD7_ConfToForm implements FormatorD7Interface {

  /**
   * @var \Drupal\cfrapi\ConfToForm\ConfToFormInterface
   */
  private $confToForm;

  /**
   * @Cf
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  public static function sta() {

    return SchemaToAnythingPartial_CallbackNoHelper::fromClassName(
      __CLASS__,
      ConfToFormInterface::class);
  }

  /**
   * @param \Drupal\cfrapi\ConfToForm\ConfToFormInterface $schema
   */
  public function __construct(ConfToFormInterface $schema) {
    $this->confToForm = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label) {
    return $this->confToForm->confGetForm($conf, $label);
  }
}
