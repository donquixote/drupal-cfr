<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

class XEvaluator_ValueProvider implements XEvaluatorInterface {

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
