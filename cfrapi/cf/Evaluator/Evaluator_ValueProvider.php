<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface;

/**
 * @Cf
 */
class Evaluator_ValueProvider implements EvaluatorInterface {

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
    return $this->schema->getPhp();
  }
}
