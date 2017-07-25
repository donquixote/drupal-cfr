<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\V2V\Group\V2V_Group_Trivial;
use Donquixote\Cf\V2V\Group\V2V_GroupInterface;

class Evaluator_Group implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface[]
   */
  private $itemEvaluators;

  /**
   * @var \Donquixote\Cf\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function createFromGroupSchema(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::create($schema, new V2V_Group_Trivial(), $schemaToAnything);
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function createFromGroupValSchema(CfSchema_GroupValInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Donquixote\Cf\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_GroupInterface $groupSchema, V2V_GroupInterface $v2v, SchemaToAnythingInterface $schemaToAnything) {

    $itemEvaluators = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {
      $itemEvaluator = StaUtil::evaluator($itemSchema, $schemaToAnything);
      if (NULL === $itemEvaluator) {
        return NULL;
      }
      $itemEvaluators[$k] = $itemEvaluator;
    }

    return new self($itemEvaluators, $v2v);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface[] $itemEvaluators
   * @param \Donquixote\Cf\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(array $itemEvaluators, V2V_GroupInterface $v2v) {
    $this->itemEvaluators = $itemEvaluators;
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $values = [];
    foreach ($this->itemEvaluators as $key => $itemEvaluator) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $values[$key] = $itemEvaluator->confGetValue($itemConf);
    }

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $phpStatements = [];
    foreach ($this->itemEvaluators as $key => $itemEvaluator) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $phpStatements[$key] = $itemEvaluator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
