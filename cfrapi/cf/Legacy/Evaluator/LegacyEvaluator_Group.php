<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class LegacyEvaluator_Group extends LegacyEvaluator_GroupBase {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $groupSchema;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return self|null
   */
  public static function create(CfSchema_GroupInterface $groupSchema, SchemaToAnythingHelperInterface $helper) {

    $itemEvaluators = [];
    foreach ($groupSchema->getItemSchemas() as $key => $itemSchema) {

      if (NULL === $itemEvaluator = $helper->schema(
        $itemSchema,
        LegacyEvaluatorInterface::class)
      ) {
        return NULL;
      }

      $itemEvaluators[$key] = $itemEvaluator;
    }

    return new self($itemEvaluators, $groupSchema);
  }

  /**
   * @param \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface[] $itemEvaluators
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   */
  public function __construct(array $itemEvaluators, CfSchema_GroupInterface $groupSchema) {
    parent::__construct($itemEvaluators);
    $this->groupSchema = $groupSchema;
  }

  /**
   * @param mixed[] $itemValues
   *
   * @return mixed
   */
  protected function itemValuesGetValue(array $itemValues) {
    return $this->groupSchema->valuesGetValue($itemValues);
  }

  /**
   * @param string[] $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  protected function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper) {
    return $this->groupSchema->itemsPhpGetPhp($itemsPhp, $helper);
  }
}
