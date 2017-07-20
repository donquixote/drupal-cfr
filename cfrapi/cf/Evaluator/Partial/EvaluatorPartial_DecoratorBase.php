<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class EvaluatorPartial_DecoratorBase implements EvaluatorPartialInterface {

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
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    return $helper->schemaConfGetValue($this->decoratedSchema, $conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {
    return $helper->schemaConfGetPhp($this->decoratedSchema, $conf);
  }
}
