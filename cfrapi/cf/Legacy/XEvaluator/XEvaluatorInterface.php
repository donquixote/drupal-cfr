<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

interface XEvaluatorInterface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper);

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper);

}
