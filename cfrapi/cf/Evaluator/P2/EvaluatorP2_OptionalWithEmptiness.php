<?php

namespace Donquixote\Cf\Evaluator\P2;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;

class EvaluatorP2_OptionalWithEmptiness implements EvaluatorP2Interface {

  /**
   * @var \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptiness;

  /**
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $decorated
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptiness
   */
  public function __construct(EvaluatorP2Interface $decorated, CfSchema_OptionalInterface $schema, EmptinessInterface $emptiness) {
    $this->decorated = $decorated;
    $this->schema = $schema;
    $this->emptiness = $emptiness;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {

    if ($this->emptiness->confIsEmpty($conf)) {
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

    if ($this->emptiness->confIsEmpty($conf)) {
      return $this->schema->getEmptyPhp();
    }

    return $this->decorated->confGetPhp($conf);
  }
}
