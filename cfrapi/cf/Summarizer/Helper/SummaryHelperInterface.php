<?php

namespace Donquixote\Cf\Summarizer\Helper;

use Donquixote\Cf\Helper\SchemaHelperBaseInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface SummaryHelperInterface extends SchemaHelperBaseInterface {

  /**
   * @param string $string
   *
   * @return string
   */
  public function translate($string);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return string|null
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf);

}
