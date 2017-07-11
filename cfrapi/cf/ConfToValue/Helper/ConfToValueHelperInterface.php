<?php

namespace Donquixote\Cf\ConfToValue\Helper;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperBaseInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface ConfToValueHelperInterface extends EvaluatorHelperBaseInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf);

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function invalidConfiguration($message);
}
