<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

class PartialEvaluator_Neutral implements PartialEvaluatorInterface {

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

    if (!$schema instanceof CfSchema_NeutralInterface) {
      return $helper->unknownSchema();
    }

    return $helper->schemaConfIsEmpty($schema->getDecorated(), $conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $neutralSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $neutralSchema, $conf, EvaluatorHelperInterface $helper) {

    if (!$neutralSchema instanceof CfSchema_NeutralInterface) {
      return $helper->unknownSchema();
    }

    return $helper->schemaConfGetValue($neutralSchema->getDecorated(), $conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $neutralSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $neutralSchema, $conf, PhpHelperInterface $helper) {

    if (!$neutralSchema instanceof CfSchema_NeutralInterface) {
      return $helper->unknownSchema();
    }

    return $helper->schemaConfGetPhp($neutralSchema->getDecorated(), $conf);
  }
}
