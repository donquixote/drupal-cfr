<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;

class XEvaluator_OptionalWithEmptyness implements XEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   */
  public function __construct(CfSchema_OptionalInterface $schema, ConfEmptynessInterface $emptyness) {
    $this->schema = $schema;
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {

    if ($this->emptyness->confIsEmpty($conf)) {
      return $this->schema->getEmptyValue();
    }

    return $helper->schemaConfGetValue(
      $this->schema->getDecorated(),
      $conf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {

    if ($this->emptyness->confIsEmpty($conf)) {
      return $this->schema->getEmptyPhp();
    }

    return $helper->schemaConfGetPhp(
      $this->schema->getDecorated(),
      $conf);
  }
}
