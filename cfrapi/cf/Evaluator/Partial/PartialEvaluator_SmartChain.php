<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

/**
 * A "chain-of-responsibility" that remembers whether a partial does or does not
 * support a schema class.
 */
class PartialEvaluator_SmartChain implements PartialEvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface[]
   *   Format: $[] = $mapper
   */
  private $mappers;

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface[]
   *   Format: $[$class] = $mapper
   */
  private $emptynessMappersByClass = [];

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface[]
   *   Format: $[$class] = $mapper
   */
  private $valueMappersByClass = [];

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface[]
   *   Format: $[$class] = $mapper
   */
  private $phpMappersByClass = [];

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluator_NoKnownSchema
   */
  private $noKnownSchema;

  /**
   * @param \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface[] $mappers
   */
  public function __construct(array $mappers) {
    $this->mappers = $mappers;
    $this->noKnownSchema = new PartialEvaluator_NoKnownSchema();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $helper
   *
   * @return bool|mixed|null
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf, EmptynessHelperInterface $helper) {

    $class = get_class($schema);

    if (isset($this->emptynessMappersByClass[$class])) {
      return $this->emptynessMappersByClass[$class]->schemaConfIsEmpty($schema, $conf, $helper);
    }

    $unknownSchema = $helper->unknownSchema();

    foreach ($this->mappers as $mapper) {
      if ($unknownSchema !== $isEmpty = $mapper->schemaConfIsEmpty($schema, $conf, $helper)) {
        $this->emptynessMappersByClass[$class] = $mapper;
        return $isEmpty;
      }
    }

    $this->emptynessMappersByClass[$class] = $this->noKnownSchema;
    return $unknownSchema;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf, EvaluatorHelperInterface $helper) {

    $class = get_class($schema);

    if (isset($this->valueMappersByClass[$class])) {
      return $this->valueMappersByClass[$class]->schemaConfGetValue($schema, $conf, $helper);
    }

    $unknownSchema = $helper->unknownSchema();

    foreach ($this->mappers as $mapper) {
      if ($unknownSchema !== $value = $mapper->schemaConfGetValue($schema, $conf, $helper)) {
        $this->valueMappersByClass[$class] = $mapper;
        return $value;
      }
    }

    $this->valueMappersByClass[$class] = $this->noKnownSchema;
    return $unknownSchema;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf, PhpHelperInterface $helper) {

    $class = get_class($schema);

    if (isset($this->phpMappersByClass[$class])) {
      return $this->phpMappersByClass[$class]->schemaConfGetPhp($schema, $conf, $helper);
    }

    $unknownSchema = $helper->unknownSchema();

    foreach ($this->mappers as $mapper) {
      if ($unknownSchema !== $php = $mapper->schemaConfGetPhp($schema, $conf, $helper)) {
        $this->phpMappersByClass[$class] = $mapper;
        return $php;
      }
    }

    $this->phpMappersByClass[$class] = $this->noKnownSchema;
    return $unknownSchema;
  }
}
