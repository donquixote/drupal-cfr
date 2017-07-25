<?php

namespace Donquixote\Cf\Evaluator;

abstract class Evaluator_DecoratorBase implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   */
  protected function __construct(EvaluatorInterface $decorated) {
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
