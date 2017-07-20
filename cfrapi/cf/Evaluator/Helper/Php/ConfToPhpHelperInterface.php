<?php

namespace Donquixote\Cf\Evaluator\Helper\Php;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperBaseInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface ConfToPhpHelperInterface extends EvaluatorHelperBaseInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|null $message
   *
   * @return string
   */
  public function unsupportedSchema(CfSchemaInterface $schema, $message = NULL);

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
