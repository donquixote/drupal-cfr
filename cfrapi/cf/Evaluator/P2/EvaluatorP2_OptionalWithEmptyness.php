<?php

namespace Donquixote\Cf\Evaluator\P2;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;

class EvaluatorP2_OptionalWithEmptyness implements EvaluatorP2Interface {

  /**
   * @var \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $decorated
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   */
  public function __construct(EvaluatorP2Interface $decorated, CfSchema_OptionalInterface $schema, EmptynessInterface $emptyness) {
    $this->decorated = $decorated;
    $this->schema = $schema;
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {

    if ($this->emptyness->confIsEmpty($conf)) {
      return $this->schema->getEmptyValue();
    }

    return $this->decorated->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {

    if ($this->emptyness->confIsEmpty($conf)) {
      return $this->schema->getEmptyPhp();
    }

    return $this->decorated->confGetPhp($conf);
  }
}
