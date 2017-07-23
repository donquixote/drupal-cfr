<?php

namespace Drupal\cfrapi\EvaluatorP2;

use Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

/**
 * @Cf
 */
class EvaluatorP2_ValueProvider implements EvaluatorP2Interface {

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
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    return $this->schema->getValue();
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {
    return $this->schema->getPhp(new CfrCodegenHelper());
  }
}
