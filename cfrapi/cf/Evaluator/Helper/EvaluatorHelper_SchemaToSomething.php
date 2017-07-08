<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Legacy\SchemaToSomething\SchemaToSomethingInterface;
use Donquixote\Cf\Legacy\XEvaluator\XEvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\ConfUtil;
use Drupal\cfrapi\Exception\InvalidConfigurationException;

abstract class EvaluatorHelper_SchemaToSomething implements EvaluatorHelperInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToSomething\SchemaToSomethingInterface
   */
  private $schemaToEvaluator;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToSomething\SchemaToSomethingInterface $schemaToEvaluator
   */
  public function __construct(SchemaToSomethingInterface $schemaToEvaluator) {

    $schemaToEvaluator->requireResultType(XEvaluatorInterface::class);

    $this->schemaToEvaluator = $schemaToEvaluator;
  }

  /**
   * @return mixed
   */
  abstract public function unknownSchema();

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {

    $evaluator = $this->schemaToEvaluator->schema($schema);

    if (NULL === $evaluator) {
      return NULL;
    }

    if (!$evaluator instanceof XEvaluatorInterface) {
      return NULL;
    }

    return $evaluator->confGetValue($conf, $this);
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

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return array
   *   Format: [$enabled, $options]
   */
  public function schemaConfGetStatusAndOptions(CfSchemaInterface $schema, $conf) {

    $isEmpty = $this->schemaConfIsEmpty($schema, $conf);

    if (TRUE === $isEmpty) {
      return [TRUE, NULL];
    }

    if (FALSE === $isEmpty) {
      return [FALSE, $conf];
    }

    // The decorated schema does not have a native emptyness.
    return ConfUtil::confGetStatusAndOptions($conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool|null
   */
  abstract protected function schemaConfIsEmpty(CfSchemaInterface $schema, $conf);
}
