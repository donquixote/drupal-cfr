<?php

namespace Donquixote\Cf\ConfToPhp\Helper;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperBaseInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface PhpHelperInterface extends EvaluatorHelperBaseInterface {

  /**
   * @return string
   */
  public function unsupportedSchema();

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return string
   */
  public function invalidConfiguration($message);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf);
}
