<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\V2V\Group\V2V_Group_Trivial;
use Donquixote\Cf\V2V\Group\V2V_GroupInterface;

class EvaluatorPartial_Group implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $groupSchema;

  /**
   * @var \Donquixote\Cf\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   *
   * @return self
   */
  public static function createFromGroupSchema(CfSchema_GroupInterface $schema) {
    return new self($schema, new V2V_Group_Trivial());
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $schema
   *
   * @return self
   */
  public static function createFromGroupValSchema(CfSchema_GroupValInterface $schema) {
    return new self($schema->getDecorated(), $schema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Donquixote\Cf\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(CfSchema_GroupInterface $groupSchema, V2V_GroupInterface $v2v) {
    $this->groupSchema = $groupSchema;
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $values = [];
    foreach ($this->groupSchema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $values[$key] = $helper->schemaConfGetValue($itemSchema, $itemConf);
    }

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $phpStatements = [];
    foreach ($this->groupSchema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $phpStatements[$key] = $helper->schemaConfGetPhp($itemSchema, $itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
