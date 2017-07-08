<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class XEvaluator_DecoratorBase implements XEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $decoratedSchema;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   */
  public function __construct(CfSchemaInterface $decoratedSchema) {
    $this->decoratedSchema = $decoratedSchema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {
    return $helper->schemaConfGetValue($this->decoratedSchema, $conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    return $helper->schemaConfGetPhp($this->decoratedSchema, $conf);
  }
}
