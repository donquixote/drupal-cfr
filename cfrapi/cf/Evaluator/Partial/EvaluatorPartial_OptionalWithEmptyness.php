<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;

class EvaluatorPartial_OptionalWithEmptyness implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   */
  public function __construct(CfSchema_OptionalInterface $schema, EmptynessInterface $emptyness) {
    $this->schema = $schema;
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

    if ($this->emptyness->confIsEmpty($conf)) {
      return $this->schema->getEmptyValue();
    }

    return $helper->schemaConfGetValue(
      $this->schema->getDecorated(),
      $conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {

    if ($this->emptyness->confIsEmpty($conf)) {
      return $this->schema->getEmptyPhp();
    }

    return $helper->schemaConfGetPhp(
      $this->schema->getDecorated(),
      $conf);
  }
}