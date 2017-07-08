<?php

namespace Donquixote\Cf\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\ConfUtil;

abstract class SchemaHelperBase implements SchemaHelperBaseInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return array
   *   Format: [$enabled, $options]
   */
  public function schemaConfGetStatusAndOptions(CfSchemaInterface $schema, $conf) {

    $isEmpty = $this->schemaConfIsEmpty($schema, $conf);

    if (TRUE === $isEmpty) {
      return [TRUE, NULL];
    }

    if (FALSE === $isEmpty) {
      return [FALSE, $conf];
    }

    // The decorated schema does not have a native emptyness.
    return ConfUtil::confGetStatusAndOptions($conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool|null
   */
  abstract protected function schemaConfIsEmpty(CfSchemaInterface $schema, $conf);
}
