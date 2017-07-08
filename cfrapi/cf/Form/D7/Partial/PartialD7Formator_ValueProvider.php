<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

class PartialD7Formator_ValueProvider implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return mixed
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required) {

    if (!$schema instanceof ValueProviderInterface) {
      return $helper->unknownSchema();
    }

    return [];
  }
}
