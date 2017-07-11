<?php

namespace Donquixote\Cf\ConfToValue;

use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelper_SchemaToAnything;
use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelper_SchemaToSomething;
use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnything_Chain;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface;

class SchemaConfToValue implements SchemaConfToValueInterface {

  /**
   * @var \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface
   */
  private $helper;

  /**
   * @return self
   */
  public static function create() {
    $schemaToAnything = SchemaToAnything_Chain::create();
    return self::createFromSTA($schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   */
  public static function createFromSTA(SchemaToAnythingInterface $schemaToAnything) {
    $helper = new ConfToValueHelper_SchemaToAnything($schemaToAnything);
    return new self($helper);
  }

  /**
   * @param \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface $schemaToEvaluator
   *
   * @return self
   */
  public static function createFromSTS(SchemaToSomethingInterface $schemaToEvaluator) {
    $schemaToEvaluator->requireResultType(EvaluatorInterface::class);
    $helper = new ConfToValueHelper_SchemaToSomething($schemaToEvaluator);
    return new self($helper);
  }

  /**
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
   */
  public function __construct(ConfToValueHelperInterface $helper) {
    $this->helper = $helper;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {
    return $this->helper->schemaConfGetValue($schema, $conf);
  }
}
