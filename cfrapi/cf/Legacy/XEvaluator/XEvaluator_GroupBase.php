<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;

abstract class XEvaluator_GroupBase implements XEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $groupSchema;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   */
  public function __construct(CfSchema_GroupInterface $groupSchema) {
    $this->groupSchema = $groupSchema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {

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

    return $this->itemValuesGetValue($values);
  }

  /**
   * @param mixed[] $itemValues
   *
   * @return mixed
   */
  abstract protected function itemValuesGetValue(array $itemValues);

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {

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

    return $this->itemsPhpGetPhp($phpStatements, $helper);
  }

  /**
   * @param string[] $itemsPhp
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  abstract protected function itemsPhpGetPhp(array $itemsPhp, PhpHelperInterface $helper);
}
