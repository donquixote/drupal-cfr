<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Legacy\SchemaToEmptyness\SchemaToEmptynessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;

class XEvaluator_Optional implements XEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   *
   * @param \Donquixote\Cf\Legacy\SchemaToEmptyness\SchemaToEmptynessInterface $helper
   *
   * @return \Closure
   */
  public static function getFactory(SchemaToEmptynessInterface $helper) {

    /**
     * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
     *
     * @return \Donquixote\Cf\Legacy\XEvaluator\XEvaluatorInterface
     */
    return function(CfSchema_OptionalInterface $schema) use ($helper) {

      if (NULL !== $emptyness = $helper->schemaGetEmptyness($schema->getDecorated())) {
        return new XEvaluator_OptionalWithEmptyness($schema, $emptyness);
      }

      return new self($schema);
    };
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(CfSchema_OptionalInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {

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
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {

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
