<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class Evaluator_DecoratorBase implements EvaluatorInterface {

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
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    return $helper->schemaConfGetValue($this->decoratedSchema, $conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    return $helper->schemaConfGetPhp($this->decoratedSchema, $conf);
  }
}
