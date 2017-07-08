<?php

namespace Donquixote\Cf\Legacy\SchemaToEvaluator\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class PartialSchemaToEvaluator_CallbackInstanceof extends PartialSchemaToEvaluator_Callback {

  /**
   * @var string
   */
  private $interface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\Cf\Legacy\SchemaToEvaluator\Partial\PartialSchemaToEvaluatorInterface
   */
  public static function createFrom(CallbackReflectionInterface $callback) {

    $params = $callback->getReflectionParameters();

    if (1 !== count($params) || !isset($params[0])) {
      return NULL;
    }

    if (NULL === $reflInterface = $params[0]->getClass()) {
      return NULL;
    }

    $interface = $reflInterface->getName();

    if ($interface === CfSchemaInterface::class) {
      return new parent($callback);
    }

    return new self($callback, $interface);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $interface
   */
  public function __construct(CallbackReflectionInterface $callback, $interface) {
    parent::__construct($callback);
    $this->interface = $interface;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface|null
   */
  public function schemaGetEvaluator(CfSchemaInterface $schema) {

    if (!$schema instanceof $this->interface) {
      return NULL;
    }

    return parent::schemaGetEvaluator($schema);
  }
}
