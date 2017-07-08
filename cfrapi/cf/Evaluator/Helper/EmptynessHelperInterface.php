<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface EmptynessHelperInterface extends EvaluatorHelperBaseInterface {

  /**
   * @return mixed|bool
   */
  public function noNaturalEmptyness();

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool|null
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf);
}
