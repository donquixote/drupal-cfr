<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

abstract class LegacyEvaluator_GroupBase implements LegacyEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface[]
   */
  private $itemEvaluators;

  /**
   * @param \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface[] $itemEvaluators
   */
  public function __construct(array $itemEvaluators) {
    $this->itemEvaluators = $itemEvaluators;
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

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $values = [];
    foreach ($this->itemEvaluators as $key => $evaluator) {
      if (array_key_exists($key, $conf)) {
        $value = $evaluator->confGetValue($conf[$key]);
      }
      else {
        $value = $evaluator->confGetValue(NULL);
      }
      $values[$key] = $value;
    }

    return $this->itemValuesGetValue($values);
  }

  /**
   * @param mixed[] $itemValues
   *
   * @return mixed
   */
  abstract protected function itemValuesGetValue(array $itemValues);

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = array();
    }

    $php_statements = array();
    foreach ($this->itemEvaluators as $key => $evaluator) {

      $key_conf = array_key_exists($key, $conf)
        ? $conf[$key]
        : NULL;

      $php_statements[$key] = $evaluator->confGetPhp($key_conf, $helper);
    }

    return $this->itemsPhpGetPhp($php_statements, $helper);
  }

  /**
   * @param string[] $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  abstract protected function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper);
}
