<?php

namespace Drupal\cfrrealm\CfrSchemaReplacer;

use Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Group\GroupSchema_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Optionless\CfSchema_Optionless_Callback;
use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_Callback;
use Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface;
use Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface;

class CfrSchemaReplacer_Hardcoded implements CfrSchemaReplacerInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   */
  private $typeToCfrSchema;

  /**
   * @var \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @param \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface $typeToCfrSchema
   * @param \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  public function __construct(
    TypeToCfrSchemaInterface $typeToCfrSchema,
    ParamToLabelInterface $paramToLabel
  ) {
    $this->typeToCfrSchema = $typeToCfrSchema;
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   *   A transformed schema.
   */
  public function schemaGetReplacement(CfSchemaInterface $cfrSchema) {

    if ($cfrSchema instanceof CfSchema_TransformableInterface) {
      return $cfrSchema->withReplacements($this);
    }

    if ($cfrSchema instanceof CfSchema_IfaceInterface) {
      return $this->typeToCfrSchema->typeGetCfrSchema(
        $cfrSchema->getInterface(),
        $cfrSchema->getContext());
    }

    if ($cfrSchema instanceof CfSchema_CallbackInterface) {
      return $this->callbackSchemaGetSchema($cfrSchema);
    }

    // No known replacement options.
    return NULL;
  }

  /**
   * @param \Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface $cfrSchema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  private function callbackSchemaGetSchema(CfSchema_CallbackInterface $cfrSchema) {
    $callback = $cfrSchema->getCallback();
    $params = $callback->getReflectionParameters();

    if (0 === $nParams = count($params)) {
      return new CfSchema_Optionless_Callback($callback);
    }

    $explicitParamSchemas = $cfrSchema->getExplicitParamSchemas();
    $explicitParamLabels = $cfrSchema->getExplicitParamLabels();
    $context = $cfrSchema->getContext();

    $paramSchemas = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamSchemas[$i])) {
        $paramSchemas[] = $this->schemaGetReplacement(
          $explicitParamSchemas[$i]);
      }
      elseif ($paramSchema = $this->paramGetCfrSchema($param, $context)) {
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
      $schema = new CfSchema_ValueToValue_Callback(
        $paramSchemas[0],
        $callback);
      $schema = $schema->withLabel($paramLabels[0]);
      return $schema;
    }

    return new GroupSchema_Callback(
      $callback,
      $paramSchemas,
      $paramLabels);
  }

  /**
   * @param \ReflectionParameter $param
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  private function paramGetCfrSchema(\ReflectionParameter $param, CfrContextInterface $context = NULL) {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $schema = $this->typeToCfrSchema->typeGetCfrSchema(
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