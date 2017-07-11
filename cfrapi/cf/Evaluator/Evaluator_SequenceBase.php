<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class Evaluator_SequenceBase implements EvaluatorInterface {

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
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

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
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
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
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  abstract protected function itemsPhpGetPhp(array $phpStatements, PhpHelperInterface $helper);
}
