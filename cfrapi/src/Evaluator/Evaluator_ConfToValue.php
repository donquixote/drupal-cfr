<?php

namespace Drupal\cfrapi\Evaluator;

use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

class Evaluator_ConfToValue implements EvaluatorInterface {

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
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    return $this->schema->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    $helper = new CfrCodegenHelper();
    return $this->schema->confGetPhp($conf, $helper);
  }
}
