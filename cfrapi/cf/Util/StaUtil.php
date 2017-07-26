<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\SummarizerInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface|null
   */
  public static function evaluator(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, EvaluatorInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface|null
   */
  public static function emptiness(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, EmptinessInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface|null
   */
  public static function summarizer(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, SummarizerInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $itemSchemas
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   */
  public static function getMultiple(array $itemSchemas, SchemaToAnythingInterface $schemaToAnything, $interface) {

    $itemObjects = [];
    foreach ($itemSchemas as $k => $itemSchema) {
      if (!$itemSchema instanceof CfSchemaInterface) {
        throw new \RuntimeException("Item schema at key $k must be instance of CfSchemaInterface.");
      }
      $itemCandidate = self::getObject($itemSchema, $schemaToAnything, $interface);
      if (NULL === $itemCandidate) {
        return NULL;
      }
      $itemObjects[$k] = $itemCandidate;
    }

    return $itemObjects;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed|null
   */
  public static function getObject(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything, $interface) {

    $object = $schemaToAnything->schema($schema, $interface);

    if (NULL === $object || !$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
