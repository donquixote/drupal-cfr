<?php

namespace Donquixote\Cf\Evaluator\Helper\Val;

use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Evaluator\Partial\EvaluatorPartialInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\ReflectionUtil;

class ConfToValueHelper_SchemaToAnything extends ConfToValueHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(SchemaToAnythingInterface $schemaToAnything) {
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {

    $evaluator = $this->schemaToAnything->schema(
      $schema,
      EvaluatorPartialInterface::class);

    if (NULL === $evaluator) {

      $schemaClass = get_class($schema);
      $partial = ReflectionUtil::objectGetPropertyValue($this->schemaToAnything, 'partial');
      $partialsGrouped = ReflectionUtil::objectGetPropertyValue($partial, 'partialsGrouped');
      $partialsBySchemaType = ReflectionUtil::objectGetPropertyValue($partial, 'partialsBySchemaType');
      $partialsByTargetType = ReflectionUtil::objectGetPropertyValue($partial, 'partialsByTargetType');

      dpm("unsupported schema class $schemaClass for evaluator", __METHOD__);
      kdpm($partialsGrouped, '$partialsGrouped');
      kdpm($partialsBySchemaType, '$partialsBySchemaType');
      kdpm($partialsByTargetType, '$partialsByTargetType');

      kdpm($this->schemaToAnything, 'STA');
      kdpm(print_r($this->schemaToAnything, TRUE), 'STA');

      throw new EvaluatorException_UnsupportedSchema(''
        . "Unable to create an Evaluator object"
        . "\nfrom schema of class $schemaClass.");
    }

    if (!$evaluator instanceof EvaluatorPartialInterface) {

      $schemaClass = get_class($schema);

      if (is_object($evaluator)) {
        $valueExport = 'a ' . get_class($evaluator) . ' object';
      }
      else if (NULL === $evaluator) {
        $valueExport = 'NULL';
      }
      else {
        $valueExport = 'a ' . gettype($evaluator) . ' value';
      }

      throw new EvaluatorException_UnsupportedSchema(''
        . "Misbehaving STA: Attempted to create an Evaluator object"
        . "\nfrom schema of class $schemaClass."
        . "\nSTA returned $valueExport instead.");
    }

    return $evaluator->confGetValue($conf, $this);
  }
}
