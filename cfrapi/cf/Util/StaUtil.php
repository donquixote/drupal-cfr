<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\P2\SummarizerP2Interface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  public static function evaluatorP2(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, EvaluatorP2Interface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  public static function emptyness(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, EmptynessInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  public static function summarizerP2(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, SummarizerP2Interface::class);
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
  private static function getObject(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything, $interface) {

    $object = $schemaToAnything->schema($schema, $interface);

    if (NULL === $object || !$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
