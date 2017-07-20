<?php

namespace Drupal\cfrapi\Evaluator;

use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

/**
 * @Cf
 */
class Evaluator_Partial_ValueProvider implements EvaluatorPartialInterface {

  /**
   * @var \Drupal\cfrapi\ValueProvider\ValueProviderInterface
   */
  private $schema;

  /**
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   */
  public function __construct(ValueProviderInterface $schema) {
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
    return $this->schema->getPhp(new CfrCodegenHelper());
  }
}
