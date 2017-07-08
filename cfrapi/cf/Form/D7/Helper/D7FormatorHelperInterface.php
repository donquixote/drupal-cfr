<?php

namespace Donquixote\Cf\Form\D7\Helper;

use Donquixote\Cf\Helper\SchemaHelperBaseInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface D7FormatorHelperInterface extends SchemaHelperBaseInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label, $required = TRUE);

  /**
   * @return mixed
   */
  public function schemaGetEmptyConf();

  /**
   * @param string $string
   * @param array $replacements
   *
   * @return string
   */
  public function translate($string, $replacements = []);

}
