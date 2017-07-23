<?php

namespace Drupal\cfrapi\EvaluatorP2;

use Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

/**
 * @Cf
 */
class Evaluator_Partial_ConfToValue implements EvaluatorP2Interface {

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
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    return $this->schema->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {
    return $this->schema->confGetPhp($conf, new CfrCodegenHelper());
  }
}
