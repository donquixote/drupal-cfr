<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

class PartialEvaluator_ValueProvider implements PartialEvaluatorInterface {

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

    if (!$schema instanceof ValueProviderInterface) {
      return $helper->unknownSchema();
    }

    return empty($conf);
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

    if (!$schema instanceof ValueProviderInterface) {
      return $helper->unknownSchema();
    }

    return $schema->getValue();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf, PhpHelperInterface $helper) {

    if (!$schema instanceof ValueProviderInterface) {
      return $helper->unknownSchema();
    }

    return $schema->getPhp($helper->getCodegenHelper());
  }
}
