<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

/**
 * @todo This belongs into the Drupal module.
 */
class XEvaluator_ConfToValue implements XEvaluatorInterface {

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
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {
    return $this->schema->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    return $this->schema->confGetPhp($conf, $helper->getCodegenHelper());
  }
}
