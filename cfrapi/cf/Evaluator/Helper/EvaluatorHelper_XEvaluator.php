<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Legacy\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Legacy\XEvaluator\XEvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class EvaluatorHelper_XEvaluator implements EvaluatorHelperInterface {

  /**
   * @var \Donquixote\Cf\Legacy\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\Legacy\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(SchemaToAnythingInterface $schemaToAnything) {
    $this->schemaToAnything = $schemaToAnything;
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

    $evaluator = $this->schemaToAnything->schema($schema, XEvaluatorInterface::class);

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
   */
  abstract public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  abstract public function invalidConfiguration($message);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return array
   *   Format: [$enabled, $options]
   */
  abstract public function schemaConfGetStatusAndOptions(CfSchemaInterface $schema, $conf);
}
