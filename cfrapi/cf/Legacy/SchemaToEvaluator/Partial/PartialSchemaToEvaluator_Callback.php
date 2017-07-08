<?php

namespace Donquixote\Cf\Legacy\SchemaToEvaluator\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class PartialSchemaToEvaluator_Callback implements PartialSchemaToEvaluatorInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  protected function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface|null
   */
  public function schemaGetEvaluator(CfSchemaInterface $schema) {

    $candidate = $this->callback->invokeArgs([$schema]);

    if (NULL === $candidate) {
      return NULL;
    }

    if ($candidate instanceof CfSchemaInterface) {
      return $candidate;
    }

    throw new \RuntimeException("The callback returned something other than a CfSchema object.");
  }
}
