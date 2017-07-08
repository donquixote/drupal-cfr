<?php

namespace Donquixote\Cf\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaHelperBaseInterface {

  /**
   * @return mixed
   */
  public function unknownSchema();

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return mixed
   */
  public function invalidConfiguration($message);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return array
   *   Format: [$enabled, $options]
   */
  public function schemaConfGetStatusAndOptions(CfSchemaInterface $schema, $conf);

}
