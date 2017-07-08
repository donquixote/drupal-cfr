<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class LegacyEvaluator_DecoratorBase implements LegacyEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface $decorated
   */
  protected function __construct(LegacyEvaluatorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    return $this->decorated->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    return $this->decorated->confGetPhp($conf, $helper);
  }
}
