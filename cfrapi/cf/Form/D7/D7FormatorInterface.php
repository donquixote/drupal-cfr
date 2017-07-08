<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface D7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label);

}
