<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface;

class EvaluatorPartial_Optional implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface $schemaToEmptyness
   *
   * @return \Closure
   */
  public static function getFactory(SchemaToEmptynessInterface $schemaToEmptyness) {

    /**
     * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
     *
     * @return \Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface
     */
    return function(CfSchema_OptionalInterface $schema) use ($schemaToEmptyness) {

      if (NULL === $emptyness = $schemaToEmptyness->schemaGetEmptyness($schema->getDecorated())) {
        return new self($schema);
      }

      return new EvaluatorPartial_OptionalWithEmptyness($schema, $emptyness);
    };
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface
   */
  public static function create(CfSchema_OptionalInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    $emptyness = $schemaToAnything->schema(
      $schema->getDecorated(),
      EmptynessInterface::class);

    if (NULL === $emptyness || !$emptyness instanceof EmptynessInterface) {
      return new self($schema);
    }

    return new EvaluatorPartial_OptionalWithEmptyness($schema, $emptyness);
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(CfSchema_OptionalInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

    if (!is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyValue();
    }

    $subConf = isset($conf['options'])
      ? $conf['options']
      : NULL;

    return $helper->schemaConfGetValue(
      $this->schema->getDecorated(),
      $subConf);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {

    if (!is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyPhp();
    }

    $subConf = isset($conf['options'])
      ? $conf['options']
      : NULL;

    return $helper->schemaConfGetPhp(
      $this->schema->getDecorated(),
      $subConf);
  }
}
