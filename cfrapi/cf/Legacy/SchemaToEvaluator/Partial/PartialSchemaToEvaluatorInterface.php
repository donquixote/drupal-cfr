<?php

namespace Donquixote\Cf\Legacy\SchemaToEvaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface PartialSchemaToEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface|null
   */
  public function schemaGetEvaluator(CfSchemaInterface $schema);

}
