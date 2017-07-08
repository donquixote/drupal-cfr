<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;

class XEvaluator_ValueToValue extends XEvaluator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $valueToValueSchema;

  /**
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   */
  public function __construct(CfSchema_ValueToValueInterface $valueToValueSchema) {
    $this->valueToValueSchema = $valueToValueSchema;
    parent::__construct($valueToValueSchema->getDecorated());
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {
    $value = parent::confGetValue($conf, $helper);
    return $this->valueToValueSchema->valueGetValue($value);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    $php = parent::confGetPhp($conf, $helper);
    return $this->valueToValueSchema->phpGetPhp($php, $helper->getCodegenHelper());
  }
}
