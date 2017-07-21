<?php

namespace Donquixote\Cf\SchemaReplacer;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\ParamToLabel\ParamToLabelInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Callback;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\TypeToSchema\TypeToSchemaInterface;

class SchemaReplacer_Hardcoded implements SchemaReplacerInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $typeToSchema;

  /**
   * @var \Donquixote\Cf\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToSchema
   * @param \Donquixote\Cf\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  public function __construct(
    TypeToSchemaInterface $typeToSchema,
    ParamToLabelInterface $paramToLabel
  ) {
    $this->typeToSchema = $typeToSchema;
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   *   A transformed schema.
   */
  public function schemaGetReplacement(CfSchemaInterface $schema) {

    if ($schema instanceof CfSchema_TransformableInterface) {
      return $schema->withReplacements($this);
    }

    if ($schema instanceof CfSchema_IfaceWithContextInterface) {
      return $this->typeToSchema->typeGetSchema(
        $schema->getInterface(),
        $schema->getContext());
    }

    if ($schema instanceof CfSchema_CallbackInterface) {
      return $this->callbackSchemaGetSchema($schema);
    }

    // No known replacement options.
    return NULL;
  }

  /**
   * @param \Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface $callbackSchema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  private function callbackSchemaGetSchema(CfSchema_CallbackInterface $callbackSchema) {
    $callback = $callbackSchema->getCallback();
    $params = $callback->getReflectionParameters();

    if (0 === $nParams = count($params)) {
      return new CfSchema_ValueProvider_Callback($callback);
    }

    $explicitParamSchemas = $callbackSchema->getExplicitParamSchemas();
    $explicitParamLabels = $callbackSchema->getExplicitParamLabels();
    $context = $callbackSchema->getContext();

    $paramSchemas = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamSchemas[$i])) {
        $paramSchemas[] = $this->schemaGetReplacement(
          $explicitParamSchemas[$i]);
      }
      elseif ($paramSchema = $this->paramGetSchema($param, $context)) {
        $paramSchemas[] = $paramSchema;
      }
      else {
        return NULL;
      }

      if (isset($explicitParamLabels[$i])) {
        $paramLabels[] = $explicitParamLabels[$i];
      }
      else {
        $paramLabels[] = $this->paramToLabel->paramGetLabel($param);
      }
    }

    if (1 === $nParams) {

      $schema = CfSchema_ValueToValue_CallbackMono::create(
        $paramSchemas[0],
        $callback);

      $schema = new CfSchema_Label($schema, $paramLabels[0]);

      return $schema;
    }

    return CfSchema_GroupVal_Callback::create(
      $callback,
      $paramSchemas,
      $paramLabels);
  }

  /**
   * @param \ReflectionParameter $param
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  private function paramGetSchema(\ReflectionParameter $param, CfContextInterface $context = NULL) {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $schema = $this->typeToSchema->typeGetSchema(
      $reflClassLike->getName(),
      $context);

    if (!$param->isOptional()) {
      return $schema;
    }

    if (NULL !== $param->getDefaultValue()) {
      // A default value other than NULL is not supported.
      return NULL;
    }

    return new CfSchema_Optional($schema);
  }

  # private function valueToValueSchemaGetReplacement(ValueToValueSchemaInterface)
}
