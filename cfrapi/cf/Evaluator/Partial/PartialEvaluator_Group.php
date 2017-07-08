<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

class PartialEvaluator_Group implements PartialEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $groupSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $helper
   *
   * @return bool|null
   *   TRUE, if $conf is both valid and empty.
   *   FALSE, if $conf is either invalid or non-empty.
   *   NULL, to let another partial decide.
   */
  public function schemaConfIsEmpty(CfSchemaInterface $groupSchema, $conf, EmptynessHelperInterface $helper) {

    if (!$groupSchema instanceof CfSchema_GroupInterface) {
      return $helper->unknownSchema();
    }

    return $helper->noNaturalEmptyness();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $groupSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $groupSchema, $conf, EvaluatorHelperInterface $helper) {

    if (!$groupSchema instanceof CfSchema_GroupInterface) {
      return $helper->unknownSchema();
    }

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return $helper->invalidConfiguration('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($groupSchema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key]) ? $conf[$key] : NULL;

      $values[$key] = $helper->schemaConfGetValue($itemSchema, $itemConf);
    }

    return $groupSchema->valuesGetValue($values);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $groupSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $groupSchema, $conf, PhpHelperInterface $helper) {

    if (!$groupSchema instanceof CfSchema_GroupInterface) {
      return $helper->unknownSchema();
    }

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($groupSchema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key]) ? $conf[$key] : NULL;

      $phpStatements[] = $helper->schemaConfGetPhp($itemSchema, $itemConf);
    }

    return $groupSchema->itemsPhpGetPhp($phpStatements, $helper->getCodegenHelper());
  }
}
