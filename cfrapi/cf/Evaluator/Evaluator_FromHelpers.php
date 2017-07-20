<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface;

class Evaluator_FromHelpers implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface
   */
  private $partial;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface
   */
  private $confToValueHelper;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface
   */
  private $confToPhpHelper;

  /**
   * @param \Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface $partial
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $confToValueHelper
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $confToPhpHelper
   */
  public function __construct(
    EvaluatorPartialInterface $partial,
    ConfToValueHelperInterface $confToValueHelper,
    ConfToPhpHelperInterface $confToPhpHelper
  ) {
    $this->partial = $partial;
    $this->confToValueHelper = $confToValueHelper;
    $this->confToPhpHelper = $confToPhpHelper;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf) {
    return $this->partial->confGetValue($conf, $this->confToValueHelper);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {
    return $this->partial->confGetPhp($conf, $this->confToPhpHelper);
  }
}
