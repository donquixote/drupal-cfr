<?php

namespace Donquixote\Cf\Legacy\SchemaToEvaluator;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface
   */
  public function schemaGetEvaluator(CfSchemaInterface $schema);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Legacy\Emptyness\EmptynessInterface
   */
  public function schemaGetEmptyness(CfSchemaInterface $schema);

}
