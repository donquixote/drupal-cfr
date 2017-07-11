<?php

namespace Drupal\cfrapi\Evaluator;

use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

/**
 * @Cf
 */
class Evaluator_ValueProvider implements EvaluatorInterface {

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
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {
    return $this->schema->getValue();
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {
    $helper = new CfrCodegenHelper();
    return $this->schema->getPhp($helper);
  }
}
