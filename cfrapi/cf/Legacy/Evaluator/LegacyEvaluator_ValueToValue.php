<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Donquixote\Cf\Legacy\SchemaToEvaluator\SchemaToEvaluatorInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class LegacyEvaluator_ValueToValue extends LegacyEvaluator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $valueToValueSchema;

  /**
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   * @param \Donquixote\Cf\Legacy\SchemaToEvaluator\SchemaToEvaluatorInterface $schemaToEvaluator
   *
   * @return self
   */
  public static function create(
    CfSchema_ValueToValueInterface $valueToValueSchema,
    SchemaToEvaluatorInterface $schemaToEvaluator)
  {
    return new self(
      $schemaToEvaluator->schemaGetEvaluator($valueToValueSchema),
      $valueToValueSchema);
  }

  /**
   * @param \Donquixote\Cf\Legacy\Evaluator\LegacyEvaluatorInterface $decorated
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   */
  public function __construct(
    LegacyEvaluatorInterface $decorated,
    CfSchema_ValueToValueInterface $valueToValueSchema)
  {
    parent::__construct($decorated);
    $this->valueToValueSchema = $valueToValueSchema;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    return $this->valueToValueSchema->valueGetValue($value);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    $php = parent::confGetPhp($conf, $helper);
    return $this->valueToValueSchema->phpGetPhp($php, $helper);
  }
}
