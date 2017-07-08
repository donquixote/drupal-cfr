<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

class XEvaluator_Optionless implements XEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   */
  public function __construct(CfSchema_OptionlessInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {
    return $this->schema->getValue();
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    return $this->schema->getPhp($helper->getCodegenHelper());
  }
}
