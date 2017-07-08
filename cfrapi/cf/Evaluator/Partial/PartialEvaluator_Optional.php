<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;

class PartialEvaluator_Optional implements PartialEvaluatorInterface {

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

    if (!$schema instanceof CfSchema_OptionalInterface) {
      return $helper->unknownSchema();
    }

    return $helper->noNaturalEmptyness();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $optionalSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $optionalSchema, $conf, EvaluatorHelperInterface $helper) {

    if (!$optionalSchema instanceof CfSchema_OptionalInterface) {
      return $helper->unknownSchema();
    }

    $decoratedSchema = $optionalSchema->getDecorated();

    list($enabled, $conf) = $helper->schemaConfGetStatusAndOptions(
      $decoratedSchema, $conf);

    if ($enabled) {
      return $helper->schemaConfGetValue($decoratedSchema, $conf);
    }

    return $optionalSchema->getEmptyValue();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $optionalSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $optionalSchema, $conf, PhpHelperInterface $helper) {

    if (!$optionalSchema instanceof CfSchema_OptionalInterface) {
      return $helper->unknownSchema();
    }

    $decoratedSchema = $optionalSchema->getDecorated();

    list($enabled, $conf) = $helper->schemaConfGetStatusAndOptions(
      $decoratedSchema, $conf);

    if ($enabled) {
      return $helper->schemaConfGetPhp($decoratedSchema, $conf);
    }

    return $optionalSchema->getEmptyPhp();
  }
}
