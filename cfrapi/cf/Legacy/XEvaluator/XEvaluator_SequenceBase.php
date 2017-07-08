<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class XEvaluator_SequenceBase implements XEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $itemSchema;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $itemSchema
   */
  protected function __construct(CfSchemaInterface $itemSchema) {
    $this->itemSchema = $itemSchema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {

    if (NULL === $conf) {
      return [];
    }

    if (!is_array($conf)) {
      return $helper->invalidConfiguration('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->invalidConfiguration("Deltas must be non-negative integers.");
      }

      $values[] = $helper->schemaConfGetValue($this->itemSchema, $itemConf);
    }

    return $this->itemValuesGetValue($values);
  }

  /**
   * @param mixed[] $values
   *
   * @return mixed
   */
  abstract protected function itemValuesGetValue(array $values);

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {

    if (NULL === $conf || [] === $conf) {
      return '[]';
    }

    if (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->incompatibleConfiguration($conf, "Sequence array keys must be non-negative integers.");
      }

      $phpStatements[] = $helper->schemaConfGetPhp($this->itemSchema, $itemConf);
    }

    return $this->itemsPhpGetPhp($phpStatements, $helper);
  }

  /**
   * @param string[] $phpStatements
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  abstract protected function itemsPhpGetPhp(array $phpStatements, PhpHelperInterface $helper);
}
