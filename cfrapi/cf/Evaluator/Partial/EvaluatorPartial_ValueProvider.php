<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface;

/**
 * @Cf
 */
class EvaluatorPartial_ValueProvider implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface $schema
   */
  public function __construct(CfSchema_ValueProviderInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    return $this->schema->getValue();
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {
    return $this->schema->getPhp();
  }
}
