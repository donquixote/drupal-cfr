<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Group\GroupSchema_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional_Null;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Callback;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_Callback;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\cfrreflection\ParamToLabel\ParamToLabel;
use Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface;

class SchemaReplacerPartial_Callback implements SchemaReplacerPartialInterface {

  /**
   * @var \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @return \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Callback
   */
  public static function create() {
    return new self(new ParamToLabel());
  }

  /**
   * @param \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  public function __construct(ParamToLabelInterface $paramToLabel) {
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    return CfSchema_CallbackInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer) {

    if (!$schema instanceof CfSchema_CallbackInterface) {
      return NULL;
    }

    $callback = $schema->getCallback();
    $params = $callback->getReflectionParameters();

    if (0 === $nParams = count($params)) {
      return new CfSchema_ValueProvider_Callback($callback);
    }

    $explicitParamSchemas = $schema->getExplicitParamSchemas();
    $explicitParamLabels = $schema->getExplicitParamLabels();
    $context = $schema->getContext();

    $paramSchemas = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamSchemas[$i])) {
        $paramSchemas[] = $replacer->schemaGetReplacement(
          $explicitParamSchemas[$i]);
      }
      elseif ($paramSchema = $this->paramGetSchema($param, $context, $replacer)) {
        $paramSchemas[] = $paramSchema;
      }
      else {
        // The callback has parameters that cannot be made configurable.
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
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  private function paramGetSchema(
    \ReflectionParameter $param,
    CfContextInterface $context = NULL,
    SchemaReplacerInterface $replacer
  ) {

    if (NULL === $reflClassLike = $param->getClass()) {
      return NULL;
    }

    $schema = new CfSchema_IfaceWithContext(
      $reflClassLike->getName(),
      $context);

    $schema = $replacer->schemaGetReplacement($schema);

    if (!$param->isOptional()) {
      return $schema;
    }

    if (NULL === $default = $param->getDefaultValue()) {
      return new CfSchema_Optional_Null($schema);
    }

    $schema = new CfSchema_Optional($schema);

    return $schema->withEmptyValue(
      $default,
      $param->getDefaultValueConstantName());
  }
}
