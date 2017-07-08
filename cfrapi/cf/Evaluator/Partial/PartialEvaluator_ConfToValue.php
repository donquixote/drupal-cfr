<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

/**
 * @todo This belongs into the Drupal module.
 */
class PartialEvaluator_ConfToValue implements PartialEvaluatorInterface {

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

    if (!$schema instanceof ConfToValueInterface) {
      return $helper->unknownSchema();
    }

    return $helper->noNaturalEmptyness();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf, EvaluatorHelperInterface $helper) {

    if (!$schema instanceof ConfToValueInterface) {
      return $helper->unknownSchema();
    }

    return $schema->confGetValue($conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf, PhpHelperInterface $helper) {

    if (!$schema instanceof ConfToValueInterface) {
      return $helper->unknownSchema();
    }

    return $schema->confGetPhp($conf, $helper->getCodegenHelper());
  }
}
