<?php

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Form\D7\FormatorD7Interface;
use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
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
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public static function formator(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return self::getObject($schema, $schemaToAnything, FormatorD7Interface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public static function formatorOptional(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $optionable = self::formatorOptionable(
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
   * @return \Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface|null
   */
  public static function formatorOptionable(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return self::getObject($schema, $schemaToAnything, OptionableFormatorD7Interface::class);
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
