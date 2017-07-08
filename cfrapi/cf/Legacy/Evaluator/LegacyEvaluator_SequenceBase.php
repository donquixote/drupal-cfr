<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Donquixote\Cf\Legacy\Emptyness\EmptynessInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence;
use Drupal\cfrapi\Exception\InvalidConfigurationException;

abstract class LegacyEvaluator_SequenceBase implements LegacyEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface
   */
  private $itemEvaluator;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $itemEmptyness;

  /**
   * @param \Donquixote\Cf\Legacy\Emptyness\EmptynessInterface $itemEmptyness
   */
  public function __construct(EmptynessInterface $itemEmptyness) {
    $this->itemEvaluator = $itemEmptyness->getEvaluator();
    $this->itemEmptyness = $itemEmptyness;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   */
  public function getEmptyness() {
    return new ConfEmptyness_Sequence($this->itemEmptyness);
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

    if (NULL === $conf) {
      return [];
    }
    if (!is_array($conf)) {
      throw new InvalidConfigurationException('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        throw new InvalidConfigurationException("Deltas must be non-negative integers.");
      }
      if ($this->itemEmptyness->confIsEmpty($deltaConf)) {
        // Skip empty values.
        continue;
      }
      $deltaValue = $this->itemEvaluator->confGetValue($deltaConf);
      $values[] = $deltaValue;
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
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    if (NULL === $conf || [] === $conf) {
      return '[]';
    }

    if (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->incompatibleConfiguration($conf, "Sequence array keys must be non-negative integers.");
      }
      if ($this->itemEmptyness->confIsEmpty($deltaConf)) {
        // Skip empty values.
        continue;
      }
      $phpStatements[] = $this->itemEvaluator->confGetPhp($deltaConf, $helper);
    }

    return $this->itemsPhpGetPhp($phpStatements, $helper);
  }

  /**
   * @param string[] $phpStatements
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  abstract protected function itemsPhpGetPhp(array $phpStatements, CfrCodegenHelperInterface $helper);
}
