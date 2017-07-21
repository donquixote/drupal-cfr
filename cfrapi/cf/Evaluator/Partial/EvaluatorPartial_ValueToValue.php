<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;

/**
 * @Cf
 */
class EvaluatorPartial_ValueToValue extends EvaluatorPartial_DecoratorBase {

  /**
   * @var \Donquixote\Cf\V2V\Value\V2V_ValueInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   */
  public function __construct(CfSchema_ValueToValueInterface $valueToValueSchema) {
    $this->v2v = $valueToValueSchema->getV2V();
    parent::__construct($valueToValueSchema->getDecorated());
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    $value = parent::confGetValue($conf, $helper);
    return $this->v2v->valueGetValue($value);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {
    $php = parent::confGetPhp($conf, $helper);
    return $this->v2v->phpGetPhp($php);
  }
}
