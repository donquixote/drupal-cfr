<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface;
use Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface;
use Donquixote\Cf\Form\D7\P2\Optionable\OptionableD7FormatorP2Interface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\P2\SummarizerP2Interface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface|null
   */
  public static function evaluatorP2(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, EvaluatorP2Interface::class);
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
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface|null
   */
  public static function summarizerP2(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, SummarizerP2Interface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public static function formatorP2(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, D7FormatorP2Interface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public static function formatorP2Optional(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $optionable = self::formatorP2Optionable(
      $schema,
      $schemaToAnything);

    if (NULL === $optionable) {
      kdpm('Sorry.', __METHOD__);
      return NULL;
    }

    return $optionable->getOptionalFormator();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\Optionable\OptionableD7FormatorP2Interface|null
   */
  public static function formatorP2Optionable(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return self::getObject($schema, $schemaToAnything, OptionableD7FormatorP2Interface::class);
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
  private static function getObject(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything, $interface) {

    $object = $schemaToAnything->schema($schema, $interface);

    if (NULL === $object || !$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
