<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;

interface EvaluatorPartialInterface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper);

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper);

}
