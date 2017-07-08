<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Helper\SchemaHelperBase;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Drupal\cfrapi\Exception\InvalidConfigurationException;

class EvaluatorHelper extends SchemaHelperBase implements EvaluatorHelperInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface
   */
  private $partialEvaluator;

  /**
   * @var \stdClass
   */
  private $unknownSchemaSymbol;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface
   */
  private $emptynessHelper;

  /**
   * @param \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface $partialEvaluator
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $emptynessHelper
   */
  public function __construct(
    PartialEvaluatorInterface $partialEvaluator,
    EmptynessHelperInterface $emptynessHelper
  ) {
    $this->partialEvaluator = $partialEvaluator;
    // Object pointers are unique.
    $this->unknownSchemaSymbol = new \stdClass();
    $this->emptynessHelper = $emptynessHelper;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf) {
    return $this->emptynessHelper->schemaConfIsEmpty($schema, $conf);
  }

  /**
   * @return mixed
   */
  public function unknownSchema() {
    return $this->unknownSchemaSymbol;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {

    $value = $this->partialEvaluator->schemaConfGetValue($schema, $conf, $this);

    if ($this->unknownSchema() === $value) {
      throw new EvaluatorException_UnsupportedSchema("Unsupported schema.");
    }

    return $value;
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function incompatibleConfiguration($conf, $message) {
    throw new InvalidConfigurationException($message);
  }

  /**
   * @param string $message
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function invalidConfiguration($message) {
    throw new InvalidConfigurationException($message);
  }
}
