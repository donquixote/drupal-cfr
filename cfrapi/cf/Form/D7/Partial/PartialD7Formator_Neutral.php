<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;

class PartialD7Formator_Neutral implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return array
   */
  public function schemaConfGetD7Form(
    CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required)
  {
    if (!$schema instanceof CfSchema_NeutralInterface) {
      return $helper->unknownSchema();
    }

    return $helper->schemaConfGetD7Form(
      $schema->getDecorated(),
      $conf,
      $label,
      $required);
  }
}
