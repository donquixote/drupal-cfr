<?php

namespace Donquixote\Cf\Evaluator\P2;

interface EvaluatorP2Interface {

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf);

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf);

}
