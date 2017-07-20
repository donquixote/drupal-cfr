<?php

namespace Donquixote\Cf\Form\D7\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface D7FormatorHelperInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label);

  /**
   * @param string $string
   * @param array $replacements
   *
   * @return string
   */
  public function translate($string, array $replacements = []);

}
