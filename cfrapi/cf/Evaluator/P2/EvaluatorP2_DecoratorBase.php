<?php

namespace Donquixote\Cf\Evaluator\P2;

abstract class EvaluatorP2_DecoratorBase implements EvaluatorP2Interface {

  /**
   * @var \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $decorated
   */
  protected function __construct(EvaluatorP2Interface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    return $this->decorated->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {
    return $this->decorated->confGetPhp($conf);
  }
}
