<?php

namespace Drupal\cfrapi\Evaluator;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

/**
 * @Cf
 */
class Evaluator_Partial_ConfToValue implements EvaluatorPartialInterface {

  /**
   * @var \Drupal\cfrapi\ConfToValue\ConfToValueInterface
   */
  private $schema;

  /**
   * @param \Drupal\cfrapi\ConfToValue\ConfToValueInterface $schema
   */
  public function __construct(ConfToValueInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    return $this->schema->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {
    return $this->schema->confGetPhp($conf, new CfrCodegenHelper());
  }
}
