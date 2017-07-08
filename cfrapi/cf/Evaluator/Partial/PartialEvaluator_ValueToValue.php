<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

class PartialEvaluator_ValueToValue implements PartialEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $helper
   *
   * @return bool|null
   *   TRUE, if $conf is both valid and empty.
   *   FALSE, if $conf is either invalid or non-empty.
   *   NULL, to let another partial decide.
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf, EmptynessHelperInterface $helper) {

    if (!$schema instanceof CfSchema_ValueToValueInterface) {
      return $helper->unknownSchema();
    }

    return $helper->schemaConfIsEmpty($schema->getDecorated(), $conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $valueToValueSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $valueToValueSchema, $conf, EvaluatorHelperInterface $helper) {

    if (!$valueToValueSchema instanceof CfSchema_ValueToValueInterface) {
      return $helper->unknownSchema();
    }

    $decoratedValue = $helper->schemaConfGetValue($valueToValueSchema->getDecorated(), $conf);

    return $valueToValueSchema->valueGetValue($decoratedValue);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $valueToValueSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $valueToValueSchema, $conf, PhpHelperInterface $helper) {

    if (!$valueToValueSchema instanceof CfSchema_ValueToValueInterface) {
      return $helper->unknownSchema();
    }

    $decoratedPhp = $helper->schemaConfGetPhp($valueToValueSchema->getDecorated(), $conf);

    return $valueToValueSchema->phpGetPhp($decoratedPhp, $helper->getCodegenHelper());
  }
}
