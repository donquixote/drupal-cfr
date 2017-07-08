<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;

/**
 * @todo This belongs into the Drupal module.
 */
class PartialD7Formator_ConfToForm implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return array|null
   */
  public function schemaConfGetD7Form(
    CfSchemaInterface $schema,
    $conf,
    $label,
    D7FormatorHelperInterface $helper,
    $required)
  {
    if (!$schema instanceof ConfToFormInterface) {
      return $helper->unknownSchema();
    }

    if (!$required) {
      return NULL;
    }

    return $schema->confGetForm($conf, $label);
  }
}
