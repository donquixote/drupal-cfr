<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

class PartialEvaluator_Sequence implements PartialEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $sequenceSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $helper
   *
   * @return bool|null
   *   TRUE, if $conf is both valid and empty.
   *   FALSE, if $conf is either invalid or non-empty.
   *   NULL, to let another partial decide.
   */
  public function schemaConfIsEmpty(CfSchemaInterface $sequenceSchema, $conf, EmptynessHelperInterface $helper) {

    if (!$sequenceSchema instanceof CfSchema_SequenceInterface) {
      // Ask someone else.
      return $helper->unknownSchema();
    }

    return $helper->noNaturalEmptyness();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $sequenceSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $sequenceSchema, $conf, EvaluatorHelperInterface $helper) {

    if (!$sequenceSchema instanceof CfSchema_SequenceInterface) {
      return $helper->unknownSchema();
    }

    $itemSchema = $sequenceSchema->getItemSchema();

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return $helper->invalidConfiguration('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $deltaConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->invalidConfiguration("Deltas must be non-negative integers.");
      }

      // We expect all "empty" values to have been cleared when the
      // configuration was saved.

      $values[] = $helper->schemaConfGetValue($itemSchema, $deltaConf);
    }

    return $sequenceSchema->valuesGetValue($values);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $sequenceSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $sequenceSchema, $conf, PhpHelperInterface $helper) {

    if (!$sequenceSchema instanceof CfSchema_SequenceInterface) {
      return $helper->unknownSchema();
    }

    $itemSchema = $sequenceSchema->getItemSchema();

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $deltaConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->incompatibleConfiguration($conf, "Sequence array keys must be non-negative integers.");
      }

      // We expect all "empty" values to have been cleared when the
      // configuration was saved.

      $phpStatements[] = $helper->schemaConfGetPhp($itemSchema, $deltaConf);
    }

    return $sequenceSchema->itemsPhpGetPhp($phpStatements, $helper->getCodegenHelper());
  }
}
